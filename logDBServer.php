#!/usr/bin/php
<?php
require_once('Log.php.inc');
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
//require_once('login.php.inc');

function logging($filename,$contents)
{
    // lookup username in databas
    // check password
   $log= fopen($filename, 'w')or die("Unable to open $filename");
   fwrite($log, $contents);
   fclose($log);
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
    case "logging":
      return logging($request['filename'],$request['contents']);
      
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}
$l= new iLog(__DIR__ ."test.log", "a");
$l->print("success!!\n");

$server = new rabbitMQServer("logDB.ini","LogDBServer");

$server->process_requests('requestProcessor');
$l->close();
exit();
?>

