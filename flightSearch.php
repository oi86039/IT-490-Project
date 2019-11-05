<!DOCTYPE html>
<?php
$cookie_name = "BE_Request";
$cookie_value = array();
$cookie_value['country'] = $_GET["country"];
$cookie_value['currency'] = $_GET["currency"];
$cookie_value['locale'] = $_GET["locale"];
$cookie_value['originPlace'] = $_GET["originPlace"];
$cookie_value['destinationPlace'] = $_GET["destinationPlace"];
$cookie_value['outboundDate'] = $_GET["outboundDate"]; //YYYY-01-06 | When to leave
$cookie_value['adults'] = $_GET["adults"]; //int
$cookie_value['email'] = $_GET["email"]; //Email checkbox val
$cookie_value['minPrice'] = $_GET["minPrice"];
$cookie_value['maxPrice'] = $_GET["maxPrice"];

//Optional tag parameters
$tags = array();
$tags['inboundDate'] = "2020-01-20"; //When to return
$tags['cabinClass'] = "economy"; //economy, premiumeconomy, business, first
$tags['children'] = 2; //int (must be 1-16 yrs old)
$tags['infants'] = 0; //int (must be >12 months old)
$tags['includeCarriers'] = ""; 
$tags['excludeCarriers'] = "";
$tags['groupPricing'] = ""; 
$cookie_value['tags'] = $tags;

setcookie($cookie_name, json_encode($cookie_value), time() + (86400 * 30), "/"); // 86400 = 1 day
?>

<meta charset="utf-8"/>

<style>
  .F1 {   width: 60% ; Background: white ;   margin:auto   }             
</style>

<fieldset class="F1">
  <legend>Select Origin/Destination</legend>

<!--Buttons for every page-->
<!--Homepage-->
<button onclick="window.location.href = '../flightSearch.php'">Flight Search</button>
<!--Go to Profile-->
<button onclick="window.location.href = 'profile.php'">Go to Profile</button>
<!--View Saved-->
<button onclick="window.location.href = 'savedResults.php'" disabled>Saved Results</button>
<!--Logout Hyperlink-->
<button onclick="window.location.href = './../index.html'">Log Out</button><br>
<!--Stop Auto Logout Checkbox + Text-->
Stop Auto-Logout<input type="checkbox" id="stop" checked>
<span id="demo"></span><br><br>

<form  action="./destSearch.php">
<!--Type Place ID here-->
<span id = "PlaceID">
Enter Place ID of desired origin: <input type= text name="OriginID" id= "OriginID" placeholder="Enter PlaceID:"  autocomplete=off required> <br>
Enter Place ID of desired destination: <input type= text name= "DestID" id="DestID" placeholder="Enter PlaceID:" autocomplete = off required> <br>
<input type = submit>
<br>
</span>

<!--Save Query button-->
<button onclick="window.location.href = '../saveResult.php'" disabled>Save Results for Later</button>

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

<br><br>
Origin <br>
Place ID &#9;|&#9; Place Name &#9;|&#9; Country ID &#9;|&#9; RegionId &#9;|&#9; City ID &#9;|&#9; Country Name <br><br>

<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('Log.php.inc');

//PHP Error Reporting
error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

//Initialize Logger
$l = new iLog(__DIR__ . '/_logs/flightSearch.log',"a");

//Initialize Client
$l->print("Setting up RMQ Client...\n");
$client = new rabbitMQClient("frontToBack.ini","frontToBack");

//Prep request
$request = array();

//Mandatory search parameters
$request['type'] = "getPlaces";
$request['country'] = $_GET["country"];
$request['currency'] = $_GET["currency"];
$request['locale'] = $_GET["locale"];
$request['originPlace'] = $_GET["originPlace"];
$request['destinationPlace'] = $_GET["destinationPlace"];
$request['outboundDate'] = $_GET["outboundDate"]; //YYYY-01-06 | When to leave
$request['adults'] = $_GET["adults"]; //int
$request['minPrice'] = $_GET["minPrice"];
$request['maxPrice'] = $_GET["maxPrice"];

//Optional tag parameters
$tags = array();
$tags['inboundDate'] = "2020-01-20"; //When to return
$tags['cabinClass'] = "economy"; //economy, premiumeconomy, business, first
$tags['children'] = 2; //int (must be 1-16 yrs old)
$tags['infants'] = 0; //int (must be >12 months old)
$tags['includeCarriers'] = ""; 
$tags['excludeCarriers'] = "";
$tags['groupPricing'] = ""; 
$request['tags'] = $tags;

//Optional Filter parameters
$filters = array();

$request['filters'] = $filters;

$l->print("Request VarDump:\n\n");
//$l->print($request);
$l->print();

//Get response
$response = $client->send_request($request);
//$response = $client->publish($request);

$l->print("Client received response: ".PHP_EOL);

//var_dump($response);

//$response = json_decode($response,true);

//var_dump($response);
$l->print("\n\n");

//var_dump($response);

//echo ("PROJECTED VALUE:". $response["Places"][0]["PlaceId"]."\n");

//Display Origin
for($i = 0; $i < count($response[0]["Places"]); $i++){
	$PlaceID = $response[0]["Places"][$i]["PlaceId"];
	$PlaceName = $response[0]["Places"][$i]["PlaceName"];
	$CountryId = $response[0]["Places"][$i]["CountryId"];
	$RegionID = $response[0]["Places"][$i]["RegionId"];
	$CityID = $response[0]["Places"][$i]["PlaceId"];
	$CountryName = $response[0]["Places"][$i]["CountryName"];
	echo "    ". $PlaceID."&#9;|&#9;".$PlaceName."&#9;|&#9;".$CountryId."&#9;|&#9;".$RegionID."&#9;|&#9;".$CityID."&#9;|&#9;".$CountryName."<br><br>";
}

echo "<br>Destination <br>
Place ID \t|\t Place Name \t|\t Country ID \t|\t; RegionId \t|\t City ID \t|\t Country Name <br><br>
";

//Display Destination
for($i = 0; $i < count($response[1]["Places"]); $i++){
	$PlaceID = $response[1]["Places"][$i]["PlaceId"];
	$PlaceName = $response[1]["Places"][$i]["PlaceName"];
	$CountryId = $response[1]["Places"][$i]["CountryId"];
	$RegionID = $response[1]["Places"][$i]["RegionId"];
	$CityID = $response[1]["Places"][$i]["PlaceId"];
	$CountryName = $response[1]["Places"][$i]["CountryName"];
	echo "<br>    ". $PlaceID."&#9;|&#9;".$PlaceName."&#9;|&#9;".$CountryId."&#9;|&#9;".$RegionID."&#9;|&#9;".$CityID."&#9;|&#9;".$CountryName."<br><br>";
}

//CLose Logger
//$l->sendToRabbitMQ(__DIR__ . '/_logs/flightSearch.log','./_logs/flightSearch.log');
$l->close();

//echo "Flight Search.php"." END".PHP_EOL;
?>
</fieldset>
