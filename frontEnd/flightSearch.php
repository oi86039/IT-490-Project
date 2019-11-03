<?php
//Flight Search Form
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


<fieldset class="F1">
  <legend>Welcome! Enter Flight Search Terms</legend>

<!--Buttons for every page-->
<!--Homepage-->
<button onclick="window.location.href = 'flightSearch.php'">Flight Search</button>
<!--Go to Profile-->
<button onclick="window.location.href = 'profile.php'" disabled>Go to Profile</button>
<!--View Saved-->
<button onclick="window.location.href = 'savedResults.php'" disabled>Saved Results</button>
<!--Logout Hyperlink-->
<button onclick="window.location.href = 'index.html'">Log Out</button><br>

<!--Stop Auto Logout Checkbox + Text-->
Stop Auto-Logout<input type="checkbox" id="stop" checked>
<span id="demo"></span><br><br>

<!--Actual thing to submit!-->
<form  action="./IT-490/flightSearch.php">
<!--Country-->
<span id = "country">
Country: <input type= text name="country" id= "country" placeholder="Enter Country:"  autocomplete=on required > <br>
</span>

<!--Currency-->
<span id = "currency">
Currency: <input type= text name="currency" id= "currency" placeholder="Enter Currency:"  autocomplete=on required> <br>
</span>

<!--Locale-->
<span id = "locale">
Locale: <input type= text name="locale" id= "locale" placeholder="Enter Locale:"  autocomplete=on required> <br>
</span>

<!--originPlace-->
<span id = "originPlace">
Origin: <input type= text name="originPlace" id= "originPlace" placeholder="Enter Origin:"  autocomplete=on required> <br>
</span>

<!--destinationPlace-->
<span id = "destinationPlace">
Destination: <input type= text name="destinationPlace" id= "destinationPlace" placeholder="Enter Destination:"  autocomplete=on required> <br>
</span>

<!--outboundDate-->
<span id = "outboundDate">
Outbound Date: <input type= date name="outboundDate" id= "outboundDate" placeholder="Enter Outbound Date:" value="2019-11-18" autocomplete=on required> <br>
</span>

<!--Adult Count-->
<span id = "adults">
Number of Adults: <input type= number name="adults" id= "adults" placeholder="1"  autocomplete=on value = 1 required> <br>
</span>

<!--Price-->
<span id = "price">
Price Range<input type= number name="minPrice" id= "minPrice" placeholder="$---.--" value = "0.01" step = "0.01"  autocomplete=on>
- <input type= number name="maxPrice" id= "maxPrice" placeholder="$---.--" value = "0.01" step = "0.01"  autocomplete=on> <br>
</span>

<span id = "email">
Email<input type=checkbox name=email id="email" placeholder="furCoat@example.com"> 
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
    window.location.href = "index.html";
}

window.onclick = reset;
window.onkeypress = reset;
window.onload = reset;
window.onmousemove = reset;

</script>

