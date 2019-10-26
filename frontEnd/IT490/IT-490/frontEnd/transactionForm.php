<?php
//Transaction Form
session_set_cookie_params(0,"/~IT490-Group9/"); //Setup cookie for session info

//Setup
error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

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
?>
<!--Begin HTML code-->
<!DOCTYPE html>
<meta charset="utf-8"/>
	<style>
    .dis{display: none;}
    .F1 {width: 60% ; Background: white ;   margin:auto}
  </style>

<form action ="transaction.php">
<fieldset class="F1">
  <legend>Transaction Form</legend>
  
<!-- Amount -->
<span id = "amount" class= "dis">
Enter Amount: <input type= text name="amount" id= "amount" placeholder="Enter Amount:"  autocomplete=off> <br>
</span>

<!-- Number of Transactions-->
<span id = "number" class= "dis">
Number of Transactions: <input type= text name="number" id= "number" placeholder=" Enter Number"  autocomplete=off> <br>
</span>
  
<!--Menu.php-->
<br>Select Account:  <?php include("menu.php"); ?>
     
<!-- Choice Menu -->
<br><br>Select a function:
<select name= "choice" id = "choice">
  <option value="nothing"> Please Select</option>
  <option value="show"> Show </option>
  <option value="deposit"> Deposit </option>
  <option value="withdraw"> Withdraw </option>
</select>
<br><br>

<!-- Mailer -->
Mail Receipt? <input type="checkbox" name= "mail" value="Y"> <br><br>

<!-- Submit Query -->
<input type=submit ><br><br>

<!--Stop Auto Logout Checkbox + Text-->
STOP_AUTO_LOGOUT<input type="checkbox" id="stop" checked>		
<span id="demo"></span><br><br>

<!--Logout Hyperlink-->
//<a href="logout.php">Log Out</a>
<button onclick="window.location.href = 'logout.php';">Logout</button>

</fieldset>
</form>

<!-- Javascript -->
<script type="text/javascript">
"use strict";
var ptrbox = document.getElementById("stop");  
var timeOut;

function reset() {
  //If not checked, monitor activity and logout if none
  if (!ptrbox.checked){
    document.getElementById("demo").innerHTML= "<h1>Will logout after 5 seconds. </h1>";
    window.clearTimeout(timeOut);
    timeOut = window.setTimeout( "redir()" , 5000 );
    }
    //If checked, do nothing
    else
    document.getElementById("demo").innerHTML="";
}

function redir() {
    if (ptrbox.checked)
    return; //Do nothing if checked
    else
    window.location.href = "Logout.php";
}

window.onclick = reset;
window.onkeypress = reset;
window.onload = reset;
window.onmousemove = reset;

//Amount and Number Reveal Code
var ptr1 = document.getElementById("choice");
ptr1.addEventListener('change', reveal);
  										
function reveal() {										

     var v1 = ptr1.value ; //Choice menu value
     var ptr2 = document.getElementById("amount");
     var ptr3 = document.getElementById("number");
     
     //Determine value and display appropriate values
     if (v1 == "nothing"){
         ptr2.style.display = "none";
         ptr3.style.display = "none";
     }
     else if (v1 == "show" ){
         ptr2.style.display = "none";
         ptr3.style.display = "block";
     }
     else if (v1 == "deposit" || v1=="withdraw"){
         ptr2.style.display = "block";
         ptr3.style.display = "none";
     }
}
</script>

