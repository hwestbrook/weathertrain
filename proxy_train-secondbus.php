<?php
header('Access-Control-Allow-Origin: *');
$url = "http://webservices.nextbus.com/service/publicXMLFeed?command=predictions&a=sf-muni&r=J&s=4006&useShortTitles=true";
$str = file_get_contents($url);
echo $str;
?>