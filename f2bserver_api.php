#!/usr/bin/php
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');
require_once('Log.php.inc');

//$L = new iLog("./backend_log.log","a");

function getPlaces($query,$country,$currency,$locale){
	//global $L;
	//$L -> print("'getPlaces' called for proper syntax");
	echo "query: ".$query."\n";	
	$curl = curl_init();

	$q = str_replace(" ", "%20",$query);

	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/autosuggest/v1.0/$country/$currency/$locale/?query=$q",
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

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		//$L -> print("ERROR: in 'getPlaces' function");
		echo "cURL Error #:" . $err;
	} else {
		$jresponse = json_decode($response,true);
		//var_dump($jresponse);
		echo "getPlaces Created for ".$query."\n";
		return $jresponse;
	}
}
function setSession($country,$currency,$locale,$origin,$dest,$leaveDate,$adults,$tags){
	//global $L;
	//$L -> print("'setSession' called");
	
	echo "sS: Origin is ".$origin."\n";
	echo "sS: Destination is ".$dest."\n";
	
	$postF = "";
	
	if(count($tags) != 0){
		foreach($tags as $t){
			$postF .= key($t)."=".$t."&";	
		}
		echo $postF." FOR EXTRA TAGS";
	}

	$postF .= "country=".$country."&currency=".$currency."&locale=".$locale."&originPlace=".$origin."&destinationPlace=".$dest."&outboundDate=".$leaveDate."&adults=".$adults;
	
	$curl = curl_init();
	
	curl_setopt_array($curl, array(
		CURLOPT_HEADER => true,
		CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/pricing/v1.0",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $postF,
		CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded",
			"x-rapidapi-host: skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
			"x-rapidapi-key: 611b8afb5amsh84f452847ecbb28p1d4ec6jsn2f48f70e985a"
		),
	));
	
	$header = curl_exec($curl);
	$locKey = substr($header, 189, 36);
	$err = curl_error($curl);
	
	curl_close($curl);
	
	if ($err) {
		echo "cURL Error #:" . $err;
		//$L -> print("ERROR: in 'setSession' function");
	} else {
		echo "Session key created: ".$locKey."\n";
		return $locKey;
	}
}
function getSession($locKey){
	//global $L;
	//$L -> print("'getSession' is called");
	
	$curl = curl_init();

	while(true){
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/pricing/uk2/v1.0/$locKey?pageIndex=0&pageSize=10",
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
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		$jresponse = json_decode($response,true);

		if($jresponse["Status"] == "UpdatesComplete"){
			break;
		}
	}
	curl_close($curl);
	
	if ($err) {
		echo "cURL Error #:" . $err;
		//$L -> print("ERROR: in 'getSession' function");
	} else {
                var_dump($jresponse);
                return $jresponse;
	}
}
function requestProcessor($request){
	//global $L;
	static $origin = 0;
	//Required Params: query, country, currency, locale, originPlace, destinationPlace, outboundDate, adults, tags[].
	//$L -> print("Request received from Front-End");

	//array should have: ()

	echo "received request".PHP_EOL;
	var_dump($request);
	if(!isset($request['type']))
	{
		//$L -> print("ERROR: unsupported message type");
		return "ERROR: unsupported message type";
  	}
  	switch ($request['type'])
  	{
		case "getPlaces":
			$setPlaces = array(getPlaces($request['originPlace'],$request['country'],$request['currency'],$request['locale']),getPlaces($request['destinationPlace'],$request['country'],$request['currency'],$request['locale']));
			return $setPlaces;
			break;
		case "getSessions":
			$locKey = setSession($request['country'],$request['currency'],$request['locale'],$request['originPlace'],$request['destinationPlace'],$request['outboundDate'],$request['adults'],$request['tags'],$request['filters']);
			
			//$L -> print("Returned setSession to FE");
			
			//tag must include: 
			//	inboundDate,
			//	cabinClass,
			//	children,
			//	infants,
			//	includeCarriers,
			//	excludeCarriers,
			//	groupPricing
		
			//$L -> print("Returned getSession to FE");
			
			return getSession($locKey,$request['filters']);
			break;
			//filter must have:
			//	sortTypeSTRING,
			//	duration,
			//	outboundarrivetime,
			//	outbounddeparttime,
			//	inboundarrivetime,
			//	inbounddeparttime,
			//	price*,
			//	sortOrderSTRING,
			//	durationNUMBER,
			//	includeCarriersSTRING,
			//	excludeCarriersSTRING,
			//	originAirportsSTRING,
			//	destinationAirportsSTRING,
			//	stopsSTRING,
			//	outboundDepartTimeSTRING,
			//	outboundDepartStartTimeSTRING,
			//	outboundDepartEndTimeSTRING,
			//	outboundArriveStartTimeSTRING,
			//	outboundArriveEndTimeSTRING,
			//	inboundDepartTimeSTRING,
			//	inboundDepartStartTimeSTRING,
			//	inboundArriveStartTimeSTRING,
			//	inboundArriveEndTimeSTRING,
			//	pageIndex,
			//	pageSize
  	}
  	return array("returnCode" => '0', 'message'=>"Server received request and processed");
}


//$L = new iLog("./backend_log.log","a");

//$L -> print("Listening session created");

$server = new rabbitMQServer("frontToBack.ini","frontToBack");

$server->process_requests('requestProcessor');
//$L -> sentToRabbitMQ("./backend_log.log","./_logs/BackEnd.log");
exit();
?>

