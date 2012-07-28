<?php
header('Access-Control-Allow-Origin: *');
$url = "http://api.wunderground.com/api/406f56b313b47308/conditions/forecast/q/pws:MSFOC1.json";
$str = file_get_contents($url);
echo $str;
?>