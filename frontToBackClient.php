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
$request['origin'] = "";
$request['destination'] = "";
$request['outboundDate'] = "2020-01-06"; //YYYY-01-06 | When to leave
$request['adults'] = 1; //int

//Optional filter parameters
$tags = array();
$tags['inboundDate'] = "2020-01-20"; //When to return
$tags['cabinClass'] = "economy"; //economy, premiumeconomy, business, first
$tags['children'] = 2; //int (must be 1-16 yrs old)
$tags['infants'] = 0; //int (must be >12 months old)
$tags['includeCarriers'] = ""; 
$tags['excludeCarriers'] = "";
$tags['groupPricing'] = ""; 
$request['tags'] = $tags;

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

