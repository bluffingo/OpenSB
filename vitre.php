<?php
//fun fact: some of the code is based on a php file that became the bases of Escargot -gr 10/6/2021
require ('lib/common.php');
set_time_limit(0);

$address = '127.0.0.1';
$port = 27002;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

socket_bind($socket, $address, $port) or die('Could not bind to address'); //0 for localhost
//socket_set_nonblock($socket);
socket_listen($socket);

while (true)
{
    // Waiting for a client...
    $client = socket_accept($socket);

    // Client connected.
    echo '- socket_accept' . PHP_EOL;

    while (true)
    {
        // Listen to client command(s).
        $input = socket_read($client, 1024000, PHP_BINARY_READ);

        // Command(s) received.
        echo '-- socket_read' . PHP_EOL;

        $action = json_decode($input, true);
        var_dump($action);
        if ($action['type'] == 'auth') //user tries to auth
        
        {
            if ($action['authType'] == 'daa') //user has DAA enabled
            
            {
                v_debugEcho("User uses BLG DAA, disconnecting");
                v_messageBox($client, "Digest Access Authentication", "squareBracket Vitre does not support DAA."); //how tf do you expect me to implement this on web client? -gr 10/6/2021
                socket_close($socket);
            }
            else
            //user doesn't have DAA enabled
            
            {
                v_debugEcho("User doesn't use BLG DAA, connecting.");
                $currentBLID = fetch("SELECT * FROM users WHERE blockland_id = ?", [$action['blid']]);
                v_debugEcho("User appears to be " . implode([$currentBLID['username']]));
                socket_write($client, json_encode(array(
                    'type' => "auth",
                    'status' => 'success'
                )) . "\n");
            }
        }
        elseif ($action['type'] == 'ping')
        { //ping because it disconnects when there's no reply.
            v_debugEcho($currentBLID['username'] . " pings, respond with pong to avoid disconnection.");
            socket_write($client, json_encode(array(
                'type' => "pong",
                'key' => $action['key']
            )) . "\n");
        }
        elseif ($action['type'] == 'getRoomList')
        { //queries rooms, why.
            v_debugEcho($currentBLID['username'] . " is currently looking for rooms.");
            $allRooms = query("SELECT * FROM vitre_rooms WHERE id = 1");
            socket_write($client, json_encode(array(
                'type' => "roomList",
                'rooms' => $allRooms
            )) . "\n");
        }
    }
    socket_close($socket);
}
?>
