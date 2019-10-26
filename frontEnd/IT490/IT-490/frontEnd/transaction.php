<?php
//Transaction page
session_set_cookie_params(0,"/~oai4/");  session_start();

//Setup
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);  
ini_set('display_errors' , 1);
include ("accounts.php");
include ("myfunctions.php");

//Run Gatekeeper (logout if bad)
gatekeeper();

//Connect to SQL
$db = mysqli_connect($hostname,$username, $password ,$project);
if (mysqli_connect_errno()) {
	  print "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
  }
echo "<br>Successfully connected to MySQL.<br>";

//Select and get all form data
mysqli_select_db( $db, $project ); 
$user   = $_SESSION["user"];
$account = getdata ( "account" );
$amount = getdata("amount");
$choice = getdata ("choice");
$number = getdata("number");
$mail="";
if (isset ($_GET ["mail"])){
  $mail="Y";}
else 
  $mail="N";
$output = "";

//Determine menu choice and function
if ($choice =="nothing"){
    $message =  "<br>Please select a valid function. Redirecting back to Transaction Form...<br>";
    $target = "TransactionForm.php";
    redirect ($message, 3, $target);
  }
else if ($choice == "show")
    show($user,$account,$number,$output);
else if ($choice == "deposit")
    deposit($user,$account,$output,$mail,$amount);
else if ($choice == "withdraw")
    withdraw($user,$account,$output,$mail,$amount);
if ($mail == "Y"){
    mailer($user, $output);}
	
echo "<br><br> Exiting MySQL...<br>" ;
mysqli_close($db);
exit ( "Transaction complete!") ;

?>
<!DOCTYPE html>
<!--Logout Hyperlink-->
<a href="Logout.php">Log Out</a>

