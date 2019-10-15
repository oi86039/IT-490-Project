<?php
$arrayURL = array(
    "X-RapidAPI-Host" => "skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
    "X-RapidAPI-Key" => "611b8afb5amsh84f452847ecbb28p1d4ec6jsn2f48f70e985a");
$results = shell_exec('GET -H \$arrayURL https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/autosuggest/v1.0/UK/GBP/en-GB/?query=Stockholm');
$arrayCode = json_decode($results);
var_dump($arrayCode);
?>
