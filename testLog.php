#!/usr/bin/php
<?php

//testLog.php
//PHP Error Reporting
//error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
//ini_set( 'display_errors', 1);

require_once("Log.php.inc");

$l = new iLog("./_logs/testLog.log","a");

$l->print("Testing log system...");
$l->done();
$l->print("Testing empty line...");
$l->print();
$l->print();
$l->done();
$l->sendToRabbitMQ("./_logs/testLog.log","./_logs/testLog.log");
?>
