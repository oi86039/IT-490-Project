#!/usr/bin/php
<?php

//testLog.php

require_once("Log.php.inc");

$l = new iLog("./_logs/testLog.log","a");

$l->print("Testing log system...");
$l->done();
$l->print("Testing empty line...");
$l->print();
$l->print();
$l->done();
$l->close();
?>
