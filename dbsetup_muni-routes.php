<?php 

	//--------------------------------------------------------------------------
  // This file refreshes the data in the table of all muni routes to pick from
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
  // 2) Get the list of agencies from our database
  //--------------------------------------------------------------------------
  $agencyquery = "SELECT `short_name` FROM `hwestbro_weathertrain`.`muni-agencies`";
	
	$agencylist = mysql_query($agencyquery);         		  // run query
	
	
	while($row = mysql_fetch_array($agencylist)) {
		// this specifies the API URL call
		$url = "http://webservices.nextbus.com/service/publicXMLFeed?command=routeList&a=" . $row['short_name'];

	  //--------------------------------------------------------------------------
	  // 3) Get data from nextbus.com
	  //--------------------------------------------------------------------------		

		// this sets up cURL and gets the data
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$str = curl_exec($ch);
		curl_close($ch);
		
		//--------------------------------------------------------------------------
	  // 3) Need to parse data
	  //--------------------------------------------------------------------------

		$routes = new SimpleXMLElement($str);
		
		//--------------------------------------------------------------------------
	  // 4) Update the table in MySQL
	  //--------------------------------------------------------------------------

		// 
		foreach($routes as $route) {
			$agency = $row['short_name'];
			$route_short_name = $route['tag'];
			$route_name = $route['title'];
			$agency_and_route = $agency . $route_short_name;

	  	$query = "REPLACE `hwestbro_weathertrain`.`muni-routes` SET `agency` = '$agency' , `route` = '$route_short_name' , `route_title` = '$route_name'";

			mysql_query($query);          					  // run query
		}
		
	}
	
	echo "success!";
	
	// =================

?>