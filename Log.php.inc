<?php

//ILOG.php
class iLog{
	private $log;
public function __construct($filename,$mode){

	$date = date("m/d/y G.i:s", time());

	$this->log = fopen ($filename,$mode) or die ("Unable to open file!");
	fwrite($this->log,
"
====================================================
$filename
$date
====================================================
");
echo "\n";
}

public function print($message = "\n"){
	echo "$message";
	fwrite($this->log,"$message");
}

public function done(){
	echo "Done.\n";
	fwrite($this->log,"Done.\n");
}

public function close(){
	fwrite($this->log,"\n");
	fclose($this->log);
	echo "\n";
}

}
?>
