<?php
//LOGOUT.PHP
session_set_cookie_params(0,"/~oai4/",web.njit.edu);
session_start();

include ("myfunctions.php");

$_SESSION = array( );
session_destroy( );
setcookie("PHPSESSID", "", time()-3600, '/~oai4/', "", 0,0);

$message =  "You have been logged out. Redirecting to Login page...<br>";
$target = "Login.php";
redirect ($message, 4, $target);

?>
