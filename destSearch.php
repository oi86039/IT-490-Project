<!DOCTYPE html>

<meta charset="utf-8"/>

<style>
  .F1 {   width: 60% ; Background: white ;   margin:auto   }             
</style>

<form  action="./IT-490/login.html">
<fieldset class="F1">
  <legend>Flight Display</legend>

<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('Log.php.inc');

//PHP Error Reporting
error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

//Initialize Logger
$l = new iLog(__DIR__ . '/_logs/destSearch.log',"a");

//Initialize Client
$l->print("Setting up RMQ Client...\n");
$client = new rabbitMQClient("frontToBack.ini","frontToBack");

//Prep request
$request = array();

//Get cookie
$c = json_decode($_COOKIE["BE_Request"],true);

//var_dump($c);

//Mandatory search parameters
$request['type'] = "getSessions";
$request['country'] = $c["country"];
$request['currency'] = $c["currency"];
$request['locale'] = $c["locale"];
$request['originPlace'] = $_GET["OriginID"];
$request['destinationPlace'] = $_GET["DestID"];
$request['outboundDate'] = $c["outboundDate"]; //YYYY-01-06 | When to leave
$request['adults'] = $c["adults"]; //int

//Optional tag parameters
//$tags = array();
//$tags['inboundDate'] = "2020-01-20"; //When to return
//$tags['cabinClass'] = "economy"; //economy, premiumeconomy, business, first
//$tags['children'] = 2; //int (must be 1-16 yrs old)
//$tags['infants'] = 0; //int (must be >12 months old)
//$tags['includeCarriers'] = ""; 
//$tags['excludeCarriers'] = "";
//$tags['groupPricing'] = ""; 
$request['tags'] =$c['tags'];

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

var_dump($response);

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
$l->sendToRabbitMQ(__DIR__ . '/_logs/flightSearch.log','./_logs/flightSearch.log');

//echo "Flight Search.php"." END".PHP_EOL;
?>
</fieldset>
