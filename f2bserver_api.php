#!/usr/bin/php
<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');
require_once('Log.php.inc');


function getPlaces($query,$country,$currency,$locale){
	global $L;
	$L -> print("'getPlaces' called for proper syntax");
	echo "\n";

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
		$L -> print("ERROR: in 'getPlaces' function");
		echo "\n";
		echo "cURL Error #:" . $err;
	} else {
		$jresponse = json_decode($response,true);
		//var_dump($jresponse);
		echo "getPlaces Created for ".$query."\n";
		$L -> print("Returned 'getPlaces' for $query");
		return $jresponse;
	}
}
function setSession($country,$currency,$locale,$origin,$dest,$leaveDate,$adults,$tags){
	global $L;
	$L -> print("'setSession' called");
	echo "\n";
	
	echo "sS: Origin is ".$origin."\n";
	echo "sS: Destination is ".$dest."\n";
	
	$postF = "";

	$a_int = intval($adults);
        $c_int = intval($tags['children']);
	$i_int = intval($tags['infants']);

	if($a_int >= 1 && ($c_int > 0 || $i_int > 0)){
		$tags['groupPricing'] = "true";
	}

	
	if(count($tags) != 0){
		foreach($tags as $key => $t){
			if($t != ""){
				$postF .= $key."=".$t."&";
			}
		}
		echo $postF." FOR EXTRA TAGS"."\n";
	}

	$postF .= "country=".$country."&currency=".$currency."&locale=".$locale."&originPlace=".$origin."&destinationPlace=".$dest."&outboundDate=".$leaveDate."&adults=".$adults;

	echo "Required + Tags: ".$postF."\n";

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
		$L -> print("ERROR: in 'setSession' function");
		echo "\n";
	} else {
		echo "Session key created: ".$locKey."\n";
		$L -> print("Returned Sessionkey for Session Search.");
		return $locKey;
	}
}
function getSession($locKey, $filters, $search){
	global $L;
	$L -> print("'getSession' is called");
	echo "\n";
	
	$curl = curl_init();

	$urlF = "";

	$c = 0;

	if(count($filters) != 0){
                foreach($tags as $key => $t){
			$c++;
			if($t != "" && $c < count($filters)){
                                $urlF .= $key."=".$t."&";
			}
			else{
				$urlF .= $key."=".$t;
			}
                }
                echo $urlF." FOR FILTERS"."\n";
        }


	while(true){
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/pricing/uk2/v1.0/$locKey?$urlF",
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
		$L -> print("ERROR: in 'getSession' function");
		echo "\n";
	} else {
		//var_dump($jresponse);
		//var_dump($jresponse['Query']);

		$jr_itineraries = $jresponse["Itineraries"];
		$jr_legs = $jresponse['Legs'];
		$jr_segments = $jresponse['Segments'];
		$jr_carriers = $jresponse['Carriers'];
		$jr_agents = $jresponse['Agents'];
		$jr_places = $jresponse['Places'];
		$jr_currencies = $jresponse['Currencies'];

		foreach($jr_itineraries as $key => $i){ //intineraries => [0]
			foreach($i as $key2 => $i2){ //[0] => {}
				if($key2 == "OutboundLegId"){ //OutboundLegId => 123412341243
					foreach($jr_legs as $key3 => $i3){
						foreach($i3 as $key4 => $i4){
							if($i3["Id"] == $i2){
								$jresponse["Itineraries"][$key][$key2] = $i3;
							}
							else{
								break;
							}
						}
					}
				}
				if($key2 == "InboundLegId"){ //InboundLegId => 243523452543
                                        foreach($jr_legs as $key3 => $i3){
                                                foreach($i3 as $key4 => $i4){
                                                        if($i3["Id"] == $i2){
                                                                $jresponse["Itineraries"][$key][$key2] = $i3;
                                                        }
                                                        else{
                                                                break;
                                                        }
                                                }
                                        }
				}
				if($key2 == "PriceOptions"){ //PriceOptions => [0]
					foreach($i2 as $key3 => $i3){ //[0] => {}
						foreach($i3 as $key4 => $i4){ //Agents => {}
							if($key4 == "Agents"){
								foreach($i4 as $key5 => $i5){ //0 => 43234
									foreach($jr_agents as $akey => $ai){//(dict)Agents => [0]
										foreach($ai as $akey2 => $ai2){//[0] => {}
											foreach($ai2 as $akey3 => $ai3){//Id => 43234
												if($akey3 == "Id"){
													if($i5 == $ai2["Id"]){	
														$jrequest["Itineraries"][$key][$key2][$key3][$key4][$key5] = $ai2["Name"];
														var_dump($jrequest["Itineraries"][$key][$key2][$key3][$key4][$key5]);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		$maxP = floatval($search["maxPrice"]);
		echo $maxP."\n";
		$minP = floatval($search["minPrice"]);
		echo $minP."\n";


		$final_jr = $jresponse["Itineraries"];

		foreach($final_jr as $k => $d){ //[0] => ()
			foreach($final_jr[$k] as $k2 => $d2){ //priceoptions => ()
				if($k2 == "PriceOptions"){
					foreach($final_jr[$k][$k2] as $k3 => $d3){
						if($k3 == "Price"){
							echo "Price = True";
							$dd3 = floatval($d3);
							if($dd3 <= $maxP && $dd3 >= $minP){
								continue;
							}
							else{
								unset($final_jr[$k][$k2]);
								echo "removed";
							}
						}
					}
				}
			}
		}


		//var_dump($final_jr);

		//return $jresponse;
		$L -> print("Returning final response for search query!");
		return $final_jr;

	}
}
function requestProcessor($request){
	global $L;
	//static $origin = 0;
	//Required Params: query, country, currency, locale, originPlace, destinationPlace, outboundDate, adults, tags[], filters[].
	$L -> print("Request received from Front-End");
	echo "\n";

	//array should have: ()

	echo "received request".PHP_EOL;
	var_dump($request);
	if(!isset($request['type']))
	{
		$L -> print("ERROR: unsupported message type");
		echo "\n";
		return "ERROR: unsupported message type";
  	}
  	switch ($request['type'])
  	{
		case "getPlaces":
			$setPlaces = array(
				getPlaces($request['originPlace'],
					$request['country'],
					$request['currency'],
					$request['locale']),
				getPlaces($request['destinationPlace'],
				$request['country'],
				$request['currency'],
				$request['locale'])
			);
			$L -> print("Returned 'getPlaces' to FE");
			return $setPlaces;
			echo "something happened in setplaces!";
			break;
		case "getSessions":
			$locKey = setSession($request['country'],
				$request['currency'],
				$request['locale'],
				$request['originPlace'],
				$request['destinationPlace'],
				$request['outboundDate'],
				$request['adults'],
				$request['tags'],
				$request['filters']);
			
			//**tag must include: 
			//	inboundDate,
			//	cabinClass,
			//	children,
			//	infants,
			//	includeCarriers,
			//	excludeCarriers,
			//	groupPricing
		
			$L -> print("Returned getSession to FE");
			$getSession = getSession($locKey,$request['filters'],$request);
			return $getSession;
			echo "something happened in getSession!";
			break;
			//**filter must have:
			//sortType - string
			//sortOrder - string
			//duration - int
			//includeCarriers - string
			//excludeCarriers - string
			//originAirports - string
			//destinationAirports - string
			//stops - string
			//outboundDepartTime - string
			//outboundDepartStartTime - string
			//outboundDepartEndTime - string
			//outboundArriveStartTime - string
			//outboundArriveEndTime - string
			//inboundDepartTime - string
			//inboundDepartStartTime - string
			//inboundDepartEndTime - string
			//inboundArriveStartTime - string
			//inboundArriveEndTime - string
			//pageIndex - int
			//pageSize - int
  	}
  	return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$L = new iLog("./backend_log.log","a");

$L -> print("Listening session created");
echo "\n";
$server = new rabbitMQServer("frontToBack.ini","frontToBack");

$server->process_requests('requestProcessor');

$L -> sendToRabbitMQ("./backend_log.log","./_logs/BackEnd.log");

exit();
?>

