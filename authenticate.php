<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("frontToDB.ini","frontToDB");

//Get HTML var
$user = $_GET["user"];
$pass = $_GET["pass"];

//Prep and send request
$request = array();
$request['type'] = "Login";
$request['username'] = $user;
$request['password'] = sha1($pass);
$request['message'] = "Sending Authentication Request to DB";
$response = $client->send_request($request);
//$response = $client->publish($request);


//Wait for response and perform function when received
echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

