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
  // 2) Get the list of agencies and routes from our database
  //--------------------------------------------------------------------------
	// remove the WHERE sf-muni thing to get all results
  $routeQuery = "SELECT `short_name` FROM `hwestbro_weathertrain`.`muni-agencies` WHERE `short_name` = 'sf-muni'";
	
	$routeList = mysql_query($routeQuery);         		  // run query
	
	
	while($row = mysql_fetch_array($routeList)) {
		// this specifies the API URL call
		$url = "http://webservices.nextbus.com/service/publicXMLFeed?command=routeConfig&a=" . $row['short_name'];

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
			// grab the agency tag, route tag
			$agency = $row['short_name'];
			$routeTag = $route['tag'];

			//iterate over all the stops
			foreach($route->stop as $stop) {
				$stopNumber = $stop['tag'];
				$stopName = addslashes($stop['title']);
				$stopLat = $stop['lat'];
				$stopLon = $stop['lon'];				
				$stopID = $stop['stopId'];			

				// put all this in the DB
				$query = "REPLACE `hwestbro_weathertrain`.`muni-stops` SET `agency` = '$agency' , `route` = '$routeTag' , `stop` = '$stopNumber' , `stop_name` = '$stopName' , `lat` = '$stopLat' , `lon` = '$stopLon' , `stop_id` = '$stopID' ";
				
				mysql_query($query);          					  // run query
			}
			unset($stop);

			// iterate over and assign directions to stops
			foreach($route->direction as $direction) {
				// grab the direction tag and direction title and name
				$directionTag = $direction['tag'];
				$directionTitle = addslashes($direction['title']);
				$directionName = $direction['name'];
				
				// iterate over each stop
				foreach($direction as $stop) {
					$stopNumber = $stop['tag'];
					
					$query = "UPDATE `hwestbro_weathertrain`.`muni-stops` SET `direction` = '$directionTag' , `direction_title` = '$directionTitle' , `direction_name` = '$directionName' WHERE `agency` = '$agency' AND `route` = '$routeTag' AND `stop` = '$stopNumber' ";
					
					mysql_query($query);          					  // run query
				}
				unset($stop);
			}
			unset($direction);
		}
		unset($route);
		
	}
	
	echo "success!";
	
	// =================

?>