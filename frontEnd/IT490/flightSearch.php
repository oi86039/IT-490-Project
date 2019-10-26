<?php
//Transaction Form
//session_set_cookie_params(0,"/~IT490-Group9/"); //Setup cookie for session info

//Setup
error_reporting(E_ERROR | E_Warning | E_PARSE | E_NOTICE);
ini_set( 'display_errors', 1);

include ("./IT-490/myfunctions.php");

//Run Gatekeeper (logout if bad)
//gatekeeper();

?>
<!--Begin HTML code-->
<!DOCTYPE html>
<meta charset="utf-8"/>

<style>
  .F1 {   width: 60% ; Background: white ;   margin:auto   }             
</style>

<form  action="./IT-490/flightSearch.php"
<fieldset class="F1">
  <legend>Welcome! Enter Flight Search Terms</legend>

<!--Country-->
<span id = "country">
Country: <input type= text name="country" id= "country" placeholder="Enter Country:"  autocomplete=on> <br>
</span>

<!--Currency-->
<span id = "currency">
Currency: <input type= text name="currency" id= "currency" placeholder="Enter Currency:"  autocomplete=on> <br>
</span>

<!--Locale-->
<span id = "locale">
Locale: <input type= text name="locale" id= "locale" placeholder="Enter Locale:"  autocomplete=on> <br>
</span>

<!--originPlace-->
<span id = "originPlace">
Origin: <input type= text name="originPlace" id= "originPlace" placeholder="Enter Origin:"  autocomplete=on> <br>
</span>

<!--destinationPlace-->
<span id = "destinationPlace">
Destination: <input type= text name="destinationPlace" id= "destinationPlace" placeholder="Enter Destination:"  autocomplete=on> <br>
</span>

<!--outboundDate-->
<span id = "outboundDate">
Outbound Date: <input type= date name="outboundDate" id= "outboundDate" placeholder="Enter Outbound Date:"  autocomplete=on> <br>
</span>

<!--Adult Count-->
<span id = "adults">
Number of Adults: <input type= number name="adults" id= "adults" placeholder="1"  autocomplete=on> <br>
</span>

<!--Optional Tags-->
<span id = "tags">
Tags: <input type= text name="tags" id= "tags" placeholder="Enter Optional Tags:"  autocomplete=on> <br>
</span>

<!--Optional Filters-->
<span id = "filters">
Filters: <input type= text name="filters" id= "filters" placeholder="Enter Optional Filters:"  autocomplete=on> <br>
</span>

<!-- Submit Query -->
<input type=submit ><br><br>

<!--Stop Auto Logout Checkbox + Text-->
STOP_AUTO_LOGOUT<input type="checkbox" id="stop" checked>		
<span id="demo"></span><br><br>

<!--Logout Hyperlink-->
<button onclick="window.location.href = 'index.html';">Log Out</button>

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

</script>

