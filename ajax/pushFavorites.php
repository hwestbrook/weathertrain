<?php 

	//--------------------------------------------------------------------------
  // This file updates the MySQL database when someone enters new data
	// It takes data from the jEditable forms on the preferences page.
  //--------------------------------------------------------------------------

  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  include 'DB.php';
  $con = mysql_connect($host,$user,$pass);
  $dbs = mysql_select_db($databaseName, $con);

  //--------------------------------------------------------------------------
  // 2) Query database for data
  //--------------------------------------------------------------------------

	$uid = $_POST["uid"];
	$transit = $_POST["transit"];

	
	if ($transit == "firstbus") { 
		$agency = $_POST["firstbus_list1"]; 
		$route = $_POST["firstbus_list2"];
		$stop = $_POST["firstbus_list3"];
	
		$query = "UPDATE `hwestbro_weathertrain`.`user_data` SET `first_commute_agency` = '$agency' , `first_commute_route` = '$route' , `first_commute_stop` = '$stop' WHERE `fb_uid` = $uid;";
	}
	else if ($transit == "secondbus") { 
		$agency = $_POST["secondbus_list1"]; 
		$route = $_POST["secondbus_list2"];
		$stop = $_POST["secondbus_list3"];
		
		$query = "UPDATE `hwestbro_weathertrain`.`user_data` SET `second_commute_agency` = '$agency' , `second_commute_route` = '$route' , `second_commute_stop` = '$stop' WHERE `fb_uid` = $uid;";
	}
	else { 
		echo "<span class='label label-important'>Update Failed</span>";
		return false; 
	}
	
  
	mysql_query($query);          					  // run query

	echo "<span class='label label-success'>Favorite Updated</span>";

?>