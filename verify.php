<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('Log.php.inc');

require_once("./myfunctions.php");

//Setup logger
$l = new iLog(__DIR__ . '/_logs/verify.log',"a");
$l->print("Initializing Logger!\n");

$l->print("Setting up RabbitMQ Client...");
$client = new rabbitMQClient("frontToDB.ini","frontToDB");
$l->done();

//Get HTML var
$l->print("Getting HTML vars...\n");
$l->print("Getting user...");
$user = $_GET["user"]; $l->done(); //will be in sha1
//Prep and send request
$l->print("Preparing RabbitMQ request...\n");
$l->print("Type: Verify...");

$request = array();
$request['type'] = "verify"; $l->done();
$request['user'] = $user;
$l->print("User: $user");
$l->print();

//Send request
$l->print("Sending request...\n");
$response = $client->send_request($request);
//$response = $client->publish($request);


//Wait for response and perform function when received
$l->print("Client received response: $response");

//if not verified, send email.
if ($response == 0){
	redirect("<br><br>Email not verified. Check your email. Redirecting in 6 seconds...<br><br>", 6,"../index.html" );
	//send request for email THROUGH login.php.inc
}
else
	redirect("<br><br>Email verified! Redirecting to login page in 6 seconds...",6,"../login.html");


$l->print("\n\n");
//$l->close();

//Send file to RMQ
$filenameOUT = __DIR__ . '/_logs/verify.log';
$filenameIN = __DIR__ . '/_logs/verify.log';
$l->sendToRabbitMQ($filenameIN, $filenameOUT);
echo "Sent Log to rabbitMQ.";


echo ($argv[0]." END".PHP_EOL);
?>
