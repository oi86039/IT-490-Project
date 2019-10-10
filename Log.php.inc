<?php

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
	echo "[".date("y/m/d  G.i:s")."] :  $message";
	fwrite($this->log,"$message");
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

}
?>
