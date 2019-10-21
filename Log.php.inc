<?php
require_once("Log.php.inc");
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');



//require_once('Log.php.inc');
//require_once("../myfunctions.php");
//ILOG.php
class iLog{
	private $log;
public function __construct($filename,$mode){
	$date = date("y/m/d G.i", time());
	$this->log = fopen ($filename,$mode) or die ("Unable to open file!");
	fwrite($this->log,
"
====================================================
$filename
Date Created: $date
====================================================
");
echo "\n";
}
public function print($message = "<br>\n"){
	if (strcmp($message,"<br>\n")==0){
	echo $message;
	fwrite($this->log,"$message");
	}
	else{	
		$nMessage = "[".date("y/m/d  G.i:s")."] :  $message";
		echo $nMessage;
	fwrite($this->log,$nMessage);
	}
}
public function done(){
	echo "Done.<br>\n";
	fwrite($this->log,"Done.<br>\n");
}
public function close(){
	fwrite($this->log,"<br>\n");
	fclose($this->log);
	echo "<br>\n";
}
public function sendToRabbitMQ($filenameIN, $filenameOUT){
fwrite($this->log,"<br>\n");
        fclose($this->log);
        echo "<br>\n";
	//Send log file to rabbitMQ
//$l->print("Setting up RabbitMQ Client...");
	$client = new rabbitMQClient("log.ini","LogServer");
$l = new iLog("Log.php.inc", "a");
//$l->done();
//$l->close();
//Prep and send request
$request = array();
$request['type'] = "logging";
$request['filename'] = $filenameOUT;
$request['contents'] = file_get_contents($filenameIN);
//Send request
$response = $client->send_request($request);
//$response = $client->publish($request);
//Wait for response and perform function when received
echo("Client received response:\n");
echo($response);
}
}
?>
