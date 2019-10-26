<?php
//MENU.PHP
$user = $_SESSION["user"];
$s = "select*from A2 where user = '$user'" ;
($t = mysqli_query($db, $s)) or die(mysqli_error($db));

//Menu Wrapper
  echo "<select name=\"account\">";
//Option Wrapper 
   while ($r = mysqli_fetch_array($t,MYSQLI_ASSOC)){
       $account = $r["account"];
       $balance = $r["current"];
   echo "<option value = \"$account\">";
     echo  "$account   |   $balance";
   echo "</option>";
   }
 //End Option Wrapper
echo "</select>";
//End Menu Wrapper
?>
