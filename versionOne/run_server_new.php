#!/usr/bin/php -q
<?php
require_once 'Lib/server_class_new.php';
require_once 'Lib/client_class.php';

set_time_limit(0);
// variables
$address = '127.0.0.1';
$port = 5003;
$verboseMode = true;
$server = new Server($address, $port, $verboseMode);
$server->run();
?>
