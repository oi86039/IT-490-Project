<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("frontToDB.ini","frontToDB");

//Get HTML var
$user = $_GET["user"];
$email = $_GET["email"];
$pass = $_GET["pass"];
$confirmPass = $_GET["confirmPass"];

//Prep and send request
$request = array();
$request['type'] = "Register";
$request['user'] = $user;
$request['email'] = $email;
$request['pass'] = sha1($pass);
$request['confirmPass'] = sha1($pass);
$request['plainPass'] = $pass;
$request['message'] = "Sending Authentication Request to DB";
$response = $client->send_request($request);
//$response = $client->publish($request);


//Wait for response and perform function when received
echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

