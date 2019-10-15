#!/usr/bin/php
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');

function gPlaces($query,$country,$currency,$locale)
{
	$curl = curl_init();

	$q2 = str_replace(" ", "%20", $query);

	curl_setopt_array($curl, array(
	CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/autosuggest/v1.0/$country/$currency/$locale/?query=$q2",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"x-rapidapi-host: skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
		"x-rapidapi-key: 611b8afb5amsh84f452847ecbb28p1d4ec6jsn2f48f70e985a"
	),
));

	$json_response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);
	
	if ($err) {
		echo "cURL Error #:" . $err;
	} else {
		$php_response = json_decode($json_response);
		var_dump($php_response);
		return $php_response;
	}
}
function gLink($country,$currency,$locale,$originPlace,$destinationPlace,$outboundDate,$adults)
{
	$linkResponse = shell_exec("php f2bGetLink.php");
	echo $linkResponse;
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "getplaces":
      return gPlaces($request['query'],$request['country'],$request['currency'],$request['locale']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("frontToBack.ini","frontToBack");

$server->process_requests('requestProcessor');
exit();
?>

