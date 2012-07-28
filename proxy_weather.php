<?php
// this disallows cross site attacks
header('Access-Control-Allow-Origin: *');

// this specifies the API URL call
$url = "http://api.wunderground.com/api/406f56b313b47308/conditions/forecast/q/pws:MSFOC1.json";

// this sets up cURL and gets the data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$str = curl_exec($ch);
curl_close($ch);

// this returns the data
echo $str;
?>