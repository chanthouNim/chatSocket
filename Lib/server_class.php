<?php
ini_set ( 'error_reporting', E_ALL ^ E_NOTICE );
ini_set ( 'display_errors', 1 );
set_time_limit ( 0 );
$address = '127.0.0.1';
$port = 6901;

$sock = socket_create ( AF_INET, SOCK_STREAM, 0 );
socket_bind ( $sock, $address, $port ) or die ( 'Could not bind to address' );
socket_listen ( $sock );
socket_set_nonblock ( $sock );

$read = array();
$clients = array();

while(true) {
	$clientNumber = count($clients);
	if ($newsock = @socket_accept ( $sock )) {
		if(is_resource($newsock)) {
			socket_write($newsock, "$clientNumber>", 2).chr(0);
			echo "New client connected $clientNumber";
			$clients[$clientNumber] = $newsock;
			$clientNumber++;
		}
	}

	if (count($clients)) {
		foreach ($clients as $key => $v) {
			if (@socket_recv($v, $string, 1024, MSG_DONTWAIT ) === 0) {
				unset ($clients[$key]);

				socket_close($v);
			} else {
				if ($string) {
					foreach ($clients as $k => $client) {
						if ($k != $key) {
							$banSent = @socket_send($client, $string, 1024, MSG_DONTROUTE);
						}
					}
					echo "$k: $string\n";
				}
			}
		}
	}
	sleep ( 1 );
}

// Close the master sockets
socket_close($sock);
?>