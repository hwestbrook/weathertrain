<?php 

		//--------------------------------------------------------------------------
		// 1) Connect to mysql database
		//--------------------------------------------------------------------------
		include 'DB.php';
		$con = mysql_connect($host,$user,$pass);
		$dbs = mysql_select_db($databaseName, $con);
     
    // Execute Query in the right order  
    //(value,text) 
    $query = "SELECT `short_name` , `name` FROM `hwestbro_weathertrain`.`muni-agencies`";

    $result = mysql_query($query); 
    $items = array(); 
    if($result &&  
       mysql_num_rows($result)>0) { 
        while($row = mysql_fetch_array($result)) { 
            $items[] = array( $row[0], $row[1] ); 
        }         
    } 
    mysql_close(); 
    // convert into JSON format and print
    echo json_encode($items);  

?>