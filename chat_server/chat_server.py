import asyncio
import websockets
import json
import random
import discord
from discord.ext import commands
from collections import defaultdict
import time
import pymysql
import config # configs

# WebSocket server variables
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

async def authenticate_with_token(token):
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
                

async def fetch_username_from_token(token):
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

@bot.event
async def on_message(message):
    if message.author == bot.user:
        return

    if message.guild.id == GUILD_ID and message.channel.id == CHANNEL_ID:
        chat_message = {
            "username": "Discord-" + message.author.display_name,
            "message": message.content
        }
        await broadcast_message(chat_message)
    await bot.process_commands(message)

async def handler(websocket, path):
    try:
        # Receive the initial message which should include the token
        initial_message = await websocket.recv()
        data = json.loads(initial_message)
        token = data.get("token")

        # Perform authentication and get username
        if not await authenticate_with_token(token):
            await websocket.send(json.dumps({"error": "Authentication failed. Invalid token."}))
            return

        username = await fetch_username_from_token(token)

        connected_clients[websocket] = username
        await notify_users(f"{username} has joined the chat")

        async for message in websocket:
            if not message:
                continue

            # Rate limiting logic and message handling
            if await check_rate_limit(username):
                try:
                    data = json.loads(message)
                    chat_message = {
                        "username": username,
                        "message": data["message"]
                    }
                    await broadcast_message(chat_message)
                    await send_to_discord(chat_message)
                except json.JSONDecodeError:
                    await websocket.send(json.dumps({"error": "Invalid JSON format"}))
            else:
                await websocket.send(json.dumps({"warning": "You are sending messages too quickly. Please slow down."}))

    except websockets.exceptions.ConnectionClosed:
        pass
    finally:
        if websocket in connected_clients:
            del connected_clients[websocket]
            await notify_users(f"{username} has left the chat")


async def notify_users(message):
    notification = json.dumps({"system": message})
    await broadcast_message_to_all(notification)

async def broadcast_message(message):
    json_message = json.dumps(message)
    await broadcast_message_to_all(json_message)

async def broadcast_message_to_all(message):
    print(f'{message}')
    send_tasks = [client.send(message) for client in connected_clients.keys()]
    results = await asyncio.gather(*send_tasks, return_exceptions=True)
    disconnected_clients = [client for client, result in zip(connected_clients.keys(), results) if isinstance(result, websockets.exceptions.ConnectionClosed)]
    for client in disconnected_clients:
        del connected_clients[client]

async def send_to_discord(message):
    channel = bot.get_channel(CHANNEL_ID)
    await channel.send(f"{message['username']}: {message['message']}")

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

async def start_websocket_server():
    async with websockets.serve(handler, "localhost", 47101):
        await asyncio.Future()

async def start_bot():
    await bot.start(TOKEN)

async def main():
    # Start both WebSocket server and Discord bot at the same time
    await asyncio.gather(
        start_websocket_server(),
        start_bot()
    )

if __name__ == "__main__":
    asyncio.run(main())