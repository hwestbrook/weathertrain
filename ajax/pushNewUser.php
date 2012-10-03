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

	$uid = $_POST["fb_uid"];
	$username = $_POST["username"];
	$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$email = $_POST["email"];
	
  $query = "INSERT INTO `hwestbro_weathertrain`.`user_data` (`fb_uid`, `username`, `first_name`, `last_name`, `email`) VALUES ('$uid', '$username', '$firstname', '$lastname', '$email');";
	mysql_query($query);          					  // run query

?>