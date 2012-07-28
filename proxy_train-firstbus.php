<?php
header('Access-Control-Allow-Origin: *');
$url = "http://webservices.nextbus.com/service/publicXMLFeed?command=predictions&a=sf-muni&r=N&s=4448&useShortTitles=true";
$str = file_get_contents($url);
echo $str;
?>