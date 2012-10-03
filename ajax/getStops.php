<?php 

		//--------------------------------------------------------------------------
		// 1) Connect to mysql database
		//--------------------------------------------------------------------------
		include 'DB.php';
		$con = mysql_connect($host,$user,$pass);
		$dbs = mysql_select_db($databaseName, $con);
     
    // Get parameters from Array
		$routeid = !empty($_GET['id']) ? $_GET['id'] : ""; 

    // if there is no city selected by GET, fetch all rows     
    $query = "SELECT `stop` , `stop_name` , `direction_name` FROM `hwestbro_weathertrain`.`muni-stops`"; 

    //  else concatenate query with city id in order to filter.
    if($routeid != "") $query.=" WHERE `route` = '$routeid' ORDER BY `stop_name` , `direction_name`";  
    else $query.=" LIMIT 10"; 

    //  fetch the results
    $result = mysql_query($query); 
    $items = array(); 
    if($result && mysql_num_rows($result)>0) { 
        while($row = mysql_fetch_array($result)) { 
            $items[] = array( $row[0], $row[1] . " - " . $row[2] ); 
        }         
    } 
    mysql_close(); 
    echo json_encode($items);  
?>