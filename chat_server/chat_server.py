import asyncio
import websockets
import json
import discord
from aiohttp import web
from discord.ext import commands
from collections import defaultdict
import time
import pymysql
from urllib.parse import parse_qs
import config  # configs

# Connected clients
connected_clients = {}

# Discord bot variables
TOKEN = config.TOKEN
GUILD_ID = config.GUILD_ID
CHANNEL_ID = config.CHANNEL_ID

# Rate limiting variables
RATE_LIMIT_SECONDS = 10
MESSAGE_LIMIT = 2
user_message_counts = defaultdict(lambda: {"count": 0, "last_message_time": 0})

# Discord bot configs
intents = discord.Intents.default()
intents.message_content = True
bot = commands.Bot(command_prefix='sb!', intents=intents)

# Database connection details
DB_USER = config.DB_USER
DB_PASSWORD = config.DB_PASSWORD
DB_NAME = config.DB_NAME

ENABLE_BLOCKLAND_RELAY = config.ENABLE_BLOCKLAND_RELAY
BLOCKLAND_VERIFY_KEY = config.BLOCKLAND_VERIFY_KEY


class ClientProtocol:
    def __init__(self, username, client_type, client):
        self.username = username
        self.client_type = client_type
        self.client = client
        self.authenticated = False


# squarebracket to sbchat
class SquareBracketWebSocketProtocol:
    def __init__(self, websocket, path):
        self.websocket = websocket
        self.path = path
        self.client_protocol = ClientProtocol(None, 'squarebracket', self.websocket)

    async def handle(self):
        try:
            # Receive the initial message which should include the token
            initial_message = await self.websocket.recv()
            data = json.loads(initial_message)
            token = data.get("token")

            # Perform authentication and get username
            if not await authenticate_squarebracket_user(token):
                await self.websocket.send(json.dumps({"error": "This squareBracket token is invalid."}))
                return

            self.client_protocol.username = await get_squarebracket_username(token)
            self.client_protocol.authenticated = True
            connected_clients[self.websocket] = self.client_protocol
            chat_message = {
                "notification": f"{self.client_protocol.username} joined the chat",
                "client": "squarebracket"
            }
            await broadcast_message(chat_message)

            async for message in self.websocket:
                if not message:
                    continue

                # Rate limiting logic and message handling
                if await check_rate_limit(self.client_protocol.username):
                    try:
                        data = json.loads(message)
                        chat_message = {
                            "username": self.client_protocol.username,
                            "message": data["message"],
                            "client": "squarebracket"
                        }
                        await broadcast_message(chat_message)
                    except json.JSONDecodeError:
                        await self.websocket.send(json.dumps({"error": "Invalid JSON format"}))
                else:
                    await self.websocket.send(
                        json.dumps({"warning": "You are sending messages too quickly. Please slow down."}))

        except websockets.exceptions.ConnectionClosed:
            pass
        finally:
            if self.websocket in connected_clients:
                del connected_clients[self.websocket]
                if self.client_protocol.authenticated:
                    chat_message = {
                        "notification": f"{self.client_protocol.username} left the chat",
                        "client": "squarebracket"
                    }
                    await broadcast_message(chat_message)


# blockland-to-sbchat
class BlocklandHTTPProtocol:
    def __init__(self, host='0.0.0.0', port=28010):
        self.host = host
        self.port = port
        self.app = web.Application()
        self.app.router.add_post('/rcvmsg', self.handle_request)
        self.client_protocol = ClientProtocol(None, 'http', None)
        self.runner = web.AppRunner(self.app)
        self.site = None

    async def handle_request(self, request):
        # the syntax is this, which is simillar to what's on conan's farming server:
        # author=Chazpelo&message=test&bl_id=999999&verifykey=&type=message
        data = await request.text()
        parsed_data = parse_qs(data)
        parsed_data_dict = {k: v[0] for k, v in parsed_data.items()}

        if parsed_data_dict["verifykey"] == BLOCKLAND_VERIFY_KEY:
            # user messages
            if parsed_data_dict["type"] == "message":
                chat_message = {
                    "username": parsed_data_dict["author"],
                    "message": parsed_data_dict["message"],
                    "client": "blockland"
                }
                await broadcast_message(chat_message)
            # user joined/left the game
            elif parsed_data_dict["type"] == "connection":
                chat_message = {
                    "notification": f'{parsed_data_dict["author"]} {parsed_data_dict["message"]}',
                    "client": "blockland"
                }
                await broadcast_message(chat_message)
        else:
            print("Invalid verify key")

    async def run(self):
        if ENABLE_BLOCKLAND_RELAY:
            await self.runner.setup()
            self.site = web.TCPSite(self.runner, self.host, self.port)
            await self.site.start()
        else:
            print("what the fuck?")


def get_client_name(client):
    client_mapping = {
        'blockland': 'Blockland',
        'squarebracket': 'squareBracket',
        'discord': 'Discord'
    }
    return client_mapping.get(client, client)


async def authenticate_squarebracket_user(token):
    try:
        connection = pymysql.connect(host='localhost',
                                     user=DB_USER,
                                     password=DB_PASSWORD,
                                     database=DB_NAME,
                                     cursorclass=pymysql.cursors.DictCursor)

        with connection.cursor() as cursor:
            # Check if token exists in the database
            sql = "SELECT token FROM `users` WHERE `token` = %s"
            cursor.execute(sql, (token,))
            result = cursor.fetchone()

            if result:
                return True
            else:
                return False

    finally:
        connection.close()


async def get_squarebracket_username(token):
    try:
        connection = pymysql.connect(host='localhost',
                                     user=DB_USER,
                                     password=DB_PASSWORD,
                                     db=DB_NAME,
                                     charset='utf8mb4',
                                     cursorclass=pymysql.cursors.DictCursor)

        with connection.cursor() as cursor:
            # Fetch username associated with the token from the database
            sql = "SELECT `name` FROM `users` WHERE `token` = %s"
            cursor.execute(sql, (token,))
            result = cursor.fetchone()

            if result:
                return result['name']
            else:
                return None

    finally:
        connection.close()


@bot.event
async def on_ready():
    print(f'{bot.user.name} has connected to Discord!')


# discord-to-sbchat
@bot.event
async def on_message(message):
    if message.author == bot.user:
        return

    if message.guild.id == GUILD_ID and message.channel.id == CHANNEL_ID:
        chat_message = {
            "username": message.author.display_name,
            "message": message.content,
            "client": "discord"
        }
        await broadcast_message(chat_message)
    await bot.process_commands(message)


# sbchat-to-blockland
async def forward_message_over_to_blockland(message, host='localhost'):
    if ENABLE_BLOCKLAND_RELAY:
        reader, writer = await asyncio.open_connection(host, 28008)
        try:
            # Forward the message
            writer.write(message.encode('cp1252'))
            await writer.drain()
        finally:
            writer.close()
            await writer.wait_closed()


async def broadcast_message(message):
    encoded_message = json.dumps(message)

    print(f'{message}')
    send_tasks = []
    for client in connected_clients.values():
        if client.client_type == 'squarebracket':
            send_tasks.append(client.client.send(encoded_message))

    # don't send any blockland stuff back over to blockland
    if message["client"] != "blockland":
        send_tasks.append(forward_message_over_to_blockland(encoded_message))

    # don't send any discord stuff back over to discord
    if message["client"] != "discord":
        send_tasks.append(forward_message_over_to_blockland(encoded_message))

    results = await asyncio.gather(*send_tasks, return_exceptions=True)
    disconnected_clients = [client for client, result in zip(connected_clients.keys(), results) if
                            isinstance(result, (websockets.exceptions.ConnectionClosed, ConnectionResetError))]
    for client in disconnected_clients:
        del connected_clients[client]


async def async_write(transport, message):
    loop = asyncio.get_event_loop()
    await loop.run_in_executor(None, transport.write, message.encode())


# sbchat-to-discord
async def send_to_discord(message):
    channel = bot.get_channel(CHANNEL_ID)
    if isinstance(message, dict) and 'username' in message:
        client_name = get_client_name(message['client'])
        await channel.send(f"[{client_name}] {message['username']}: {message['message']}")
    elif isinstance(message, dict) and 'notification' in message:
        client_name = get_client_name(message['client'])
        await channel.send(f"[{client_name}] {message['notification']}")
    else:
        await channel.send(f"{message}")


# ratelimit squarebracket users
async def check_rate_limit(username):
    current_time = time.time()

    if username in user_message_counts:
        user_data = user_message_counts[username]
        if current_time - user_data["last_message_time"] < RATE_LIMIT_SECONDS:
            if user_data["count"] >= MESSAGE_LIMIT:
                # Send a warning message to the user
                user_data["count"] += 1
                return False
            else:
                user_data["count"] += 1
        else:
            user_data["last_message_time"] = current_time
            user_data["count"] = 1
    else:
        # Initialize user_data for new users
        user_message_counts[username] = {
            "last_message_time": current_time,
            "count": 1
        }

    return True


async def websocket_handler(websocket, path):
    protocol = SquareBracketWebSocketProtocol(websocket, path)
    await protocol.handle()


async def start_websocket_server():
    async with websockets.serve(websocket_handler, "0.0.0.0", 47101):
        await asyncio.Future()


async def start_http_blockland_server():
    protocol = BlocklandHTTPProtocol('0.0.0.0', 28010)
    await protocol.run()


async def start_bot():
    await bot.start(TOKEN)


async def main():
    print("Starting sbChat...")
    # start all of the stuff
    tasks = [start_websocket_server(), start_bot()]

    if ENABLE_BLOCKLAND_RELAY:
        tasks.append(start_http_blockland_server())

    await asyncio.gather(*tasks)


if __name__ == "__main__":
    asyncio.run(main())
