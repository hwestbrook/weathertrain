<?php

// this disallows cross site attacks
header('Access-Control-Allow-Origin: *');

// get the weather station requested
$agency = $_GET["agency"];
$route = $_GET["route"];
$stop = $_GET["stop"];

// this specifies the API URL call
$url = "http://webservices.nextbus.com/service/publicXMLFeed?command=predictions&a=" . $agency . "&r=" . $route . "&s=" . $stop . "&useShortTitles=true";

// this sets up cURL and gets the data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($ch);
curl_close($ch);

// this returns the data
echo $str;

?>