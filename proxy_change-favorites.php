<?php 

  //--------------------------------------------------------------------------
  // 1) Connect to mysql database
  //--------------------------------------------------------------------------
  include 'DB.php';
  $con = mysql_connect($host,$user,$pass);
  $dbs = mysql_select_db($databaseName, $con);

  //--------------------------------------------------------------------------
  // 2) Query database for data
  //--------------------------------------------------------------------------

	$id = $_POST["id"];
	$value = $_POST["value"];
	$uid = $_GET["fb_uid"];
	
	if ($id == "WS") { $changed = "weather_station"; }
	else if ($id == "FA") { $changed = "first_commute_agency"; }
	else if ($id == "FR") { $changed = "first_commute_route"; }
	else if ($id == "FS") { $changed = "first_commute_stop"; }
	else if ($id == "SA") { $changed = "second_commute_agency"; }
	else if ($id == "SR") { $changed = "second_commute_route"; }
	else if ($id == "SS") { $changed = "second_commute_stop"; }
	
  $query = "UPDATE `hwestbro_weathertrain`.`user_data` SET `$changed` = '$value' WHERE `fb_uid` = $uid;";
	mysql_query($query);          					  // run query

	echo $value;

?>