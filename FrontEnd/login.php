<?php
//LOGIN.PHP

//Session setup
session_set_cookie_params(0,"/~oai4/"); //Setup cookie for session info
session_start();

error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

include ("accounts.php"); //Database php file
include ("myfunctions.php");

//Connect to MySQL
$db = mysqli_connect($hostname,$username, $password ,$project);
if (mysqli_connect_errno()) {
	  print "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
print "<br>Successfully connected to MySQL.<br>";
//Retrieve username, password, and delay
mysqli_select_db( $db, $project ); 
$user = getdata("user");
$pass = getdata("pass");
$delay = $_GET["delay"];
if ($delay==null) //If no delay specified
  $delay = 0;

//Authenticate
if (!auth($user, $pass)){
  $target = "Login.html";
  //Redirect to first page if bad
  $message = "Incorrect Username/password. Redirecting to $target";
  redirect ($message, $delay, $target);
}

//If good
else{
$_SESSION ["logged"] = true;
$_SESSION ["user"] = $user;
//Redirect to authorized page if good
$message =  "Redirecting to authorized page...<br>";
$target = "TransactionForm.php";
redirect ($message, $delay, $target);
}
?>
