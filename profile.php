<!DOCTYPE html>

<meta charset="utf-8"/>

<style>
  .F1 {   width: 60% ; Background: white ;   margin:auto   }             
</style>

<fieldset class="F1">
  <legend>User Profile</legend>

<!--Buttons for every page-->
<!--Homepage-->
<button onclick="window.location.href = '../flightSearch.php'">Flight Search</button>
<!--Go to Profile-->
<button onclick="window.location.href = 'profile.php'" disabled>Go to Profile</button>
<!--View Saved-->
<button onclick="window.location.href = 'savedResults.php'" disabled>Saved Results</button>
<!--Logout Hyperlink-->
<button onclick="window.location.href = './../index.html'">Log Out</button><br>
<!--Stop Auto Logout Checkbox + Text-->
Stop Auto-Logout<input type="checkbox" id="stop" checked>
<span id="demo"></span><br><br>

<!-- Javascript -->
<script type="text/javascript">
"use strict";
var ptrbox = document.getElementById("stop");  
var timeOut;

function reset() {
  //If not checked, monitor activity and logout if none
  if (!ptrbox.checked){
    document.getElementById("demo").innerHTML= "<h1>Will logout after 5 seconds. </h1>";
    window.clearTimeout(timeOut);
    timeOut = window.setTimeout( "redir()" , 5000 );
    }
    //If checked, do nothing
    else
    document.getElementById("demo").innerHTML="";
}

function redir() {
    if (ptrbox.checked)
    return; //Do nothing if checked
    else
    window.location.href = "./../index.html";
}

window.onclick = reset;
window.onkeypress = reset;
window.onload = reset;
window.onmousemove = reset;

</script>

<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('Log.php.inc');

//PHP Error Reporting
error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

//Initialize Logger
$l = new iLog(__DIR__ . '/_logs/profile.log',"a");

//Initialize Client
$l->print("Setting up RMQ Client...\n");
$client = new rabbitMQClient("frontToDB.ini","frontToDB");

//Prep request
$request = array();

//Get cookie
$c = json_decode($_COOKIE["prof"],true);

//Send display request to login.php.inc
$request['type'] = "profile";
$request['user'] = $c;

$l->print("Request VarDump:\n\n");
//$l->print($request);
$l->print();

//Get response
$response = $client->send_request($request);
//$response = $client->publish($request);

$l->print("Client received response: ".PHP_EOL);

//$response = json_decode($response,true);

//var_dump($response);
$l->print("\n\n");

$output = "";

//Display response values 
$output.= "Username: ".$c."<br>
	Home Airport: ".$response[0]"<br>
	<br>
	Favorite(s) List:<br>";
for ($i = 1; $i < count($response); $i++){
$output.= $response[$i]."<br>";
}

echo $output;
$l->print("$output\n");

//CLose Logger
//$l->sendToRabbitMQ(__DIR__ . '/_logs/flightSearch.log','./_logs/flightSearch.log');
$l->close();

//echo "Flight Search.php"." END".PHP_EOL;
?>
</fieldset>
