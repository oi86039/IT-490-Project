#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('login.php.inc');

//include ("accounts.php");
//include ("myfunctions.php");

function doLogin($username,$password)
{
    // lookup username in database
    // check password
    $login = new loginDB();
    return $login->validateLogin($username,$password);
    //return false if not valid
}

function doRegister($user,$email,$pass,$confirmPass,$plainPass)
{
    // insert new account into DB
    $register = new loginDB();
    return $register->register($user,$email,$pass,$confirmPass,$plainPass);
    //return false if not valid
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
    case "Login":
      return doLogin($request['username'],$request['password']);
    case "Register":
	    return doRegister(
		    $request['user'],
		    $request['email'],
		    $request['pass'],
		    $request['confirmPass'],
		    $request['plainPass']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("frontToDB.ini","frontToDB");

$server->process_requests('requestProcessor');
exit();
?>

