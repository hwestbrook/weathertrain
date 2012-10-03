<?php 

	//--------------------------------------------------------------------------
  // This file refreshes the data in the table of all muni stations to pick from
	// This should only be run by an admin
  //--------------------------------------------------------------------------

	// this disallows cross site attacks
	header('Access-Control-Allow-Origin: *');

  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  include 'DB.php';
  $con = mysql_connect($host,$user,$pass);
  $dbs = mysql_select_db($databaseName, $con);

  //--------------------------------------------------------------------------
  // 2) Get data from nextbus.com
  //--------------------------------------------------------------------------

	// this specifies the API URL call
	$url = "http://webservices.nextbus.com/service/publicXMLFeed?command=agencyList";
	
	// this sets up cURL and gets the data
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$str = curl_exec($ch);
	curl_close($ch);

  //--------------------------------------------------------------------------
  // 3) Need to parse data
  //--------------------------------------------------------------------------

	$agencies = new SimpleXMLElement($str);

  //--------------------------------------------------------------------------
  // 4) Update the table in MySQL
  //--------------------------------------------------------------------------
	
	// 
	foreach($agencies as $agency) {
		$shortname = $agency['tag'];
		$name = $agency['title'];
		$region = $agency['regionTitle'];
		
  	$query = "REPLACE `hwestbro_weathertrain`.`muni-agencies` SET `short_name` = '$shortname' , `name` = '$name' , `region` = '$region'";
		
		mysql_query($query);          					  // run query
	}
	
	echo "success!";
	
	// =================

?>