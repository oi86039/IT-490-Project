#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
//require_once('Log.php.inc');

//PHP Error Reporting
error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

//Initialize Logger
//$l = new iLog(__DIR__ . '/_logs/flightSearch.log',"a");

//Initialize Client
//$l->print("Setting up RMQ Client...\n");
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
$request['outboundDate'] = date_format($_GET["outboundDate"],"YYYY-mm-dd"); //YYYY-01-06 | When to leave
$request['adults'] = $_GET["adults"]; //int

//Optional tag parameters
$tags = array();
//$tags['inboundDate'] = "2020-01-20"; //When to return
//$tags['cabinClass'] = "economy"; //economy, premiumeconomy, business, first
//$tags['children'] = 2; //int (must be 1-16 yrs old)
//$tags['infants'] = 0; //int (must be >12 months old)
//$tags['includeCarriers'] = ""; 
//$tags['excludeCarriers'] = "";
//$tags['groupPricing'] = ""; 
$request['tags'] = $tags;

//Optional Filter parameters
$filters = array();

$request['filters'] = $filters;

//$l->print("Request VarDump:\n\n")
//$l->print($request);
//$l->print();

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

//CLose Logger
//$l->close();

echo $argv[0]." END".PHP_EOL;

