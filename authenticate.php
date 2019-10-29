<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('Log.php.inc');

require_once("./myfunctions.php");

//Setup logger
$l = new iLog(__DIR__ . '/_logs/authenticate.log',"a");
$l->print("Initializing Logger!\n");

$l->print("Setting up RabbitMQ Client...");
$client = new rabbitMQClient("frontToDB.ini","frontToDB");
$l->done();

//Get HTML var
$l->print("Getting HTML vars...\n");
$l->print("Getting user...");
$user = $_POST["user"]; $l->done();
$l->print("Getting pass...");
$pass = $_POST["pass"]; $l->done();

//Prep and send request
$l->print("Preparing RabbitMQ request...\n");
$l->print("Type: Login...");

$request = array();
$request['type'] = "Login"; $l->done();
$l->print("User: $user");
$request['username'] = $user; $l->done();
$l->print("Hashed Pass:".sha1($pass));
$request['password'] = sha1($pass);$l->done();
$l-print("Message");
$request['message'] = "Sending Authentication Request to DB";

$l->print();

//Send request
$l->print("Sending request...\n");
$response = $client->send_request($request);
//$response = $client->publish($request);


echo "test";

//Wait for response and perform function when received
$l->print("Client received response: $response");

//if incorrect login, redirect user to login page.
if ($response == 0){
	redirect("Authentication Failed. Redirecting back to Login Page...", 6,"../login.html" );
}

else
	redirect("Going to flightSearch page.",6,"../flightSearch.php");

$l->print("\n\n");
//$l->close();

//Send file to RMQ
$filenameOUT = __DIR__ . '/_RLogs/authenticate.log';
$filenameIN = __DIR__ . '/_logs/authenticate.log';
//$l->close();
$l->sendToRabbitMQ($filenameIN, $filenameOUT);
echo "Sent Log to rabbitMQ.";


echo ($argv[0]." END".PHP_EOL);
?>
