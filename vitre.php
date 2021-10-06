<?php
//fun fact: some of the code is based on a php file that became the bases of Escargot -gr 10/6/2021
require('lib/common.php');
set_time_limit(0);

$address = '127.0.0.1';
$port = 27002;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

socket_bind($socket, $address, $port) or die('Could not bind to address');  //0 for localhost

//socket_set_nonblock($socket);

socket_listen($socket);

while( true )
{
	// Waiting for a client...
	$client = socket_accept($socket);

	// Client connected.
	echo '- socket_accept'.PHP_EOL;
	
	while( true )
	{
		// Listen to client command(s).
		$input = socket_read($client, 1024000, PHP_BINARY_READ);
			
		// Command(s) received.
		echo '-- socket_read'.PHP_EOL;
		
		$test = json_decode($input, true);
		var_dump ($test);
		echo $test['authType'];
		if ($test['authType'] == 'daa') {
			v_debugEcho("User uses BLG DAA, disconnecting");
			v_messageBox($client, "Digest Access Authentication", "squareBracket Vitre does not support DAA."); //how tf do you expect me to implement this on web client? -gr 10/6/2021
			socket_close($socket);
		}
	}
	for( $i = 0; $i < count($test); $i ++ )
	{
		$line = $lines[$i];
		echo "test";
	}
}
socket_close($socket);
?>