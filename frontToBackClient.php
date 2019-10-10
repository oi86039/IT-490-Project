#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("frontToBack.ini","frontToBack");

$request = array();

//Mandatory search parameters
$request['type'] = "getplaces";
$request['query'] = "Atlantic City";
$request['country'] = "US";
$request['currency'] = "USD";
$request['locale'] = "en-US";
//Optional filter parameters

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

