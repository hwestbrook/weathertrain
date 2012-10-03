<?

    // constants
    require_once("constants.php");

    // connect to database
    mysql_connect(DB_SERVER, DB_USER, DB_PASS);

    // select database
    mysql_select_db(DB_NAME);

    // prepare an array for cities
    $cities = array();

    // ensure each parameter is in lat,lng format
    if (!preg_match("/^-?\d+(?:\.\d+)?,-?\d+(?:\.\d+)?$/", $_GET["sw"]) ||
        !preg_match("/^-?\d+(?:\.\d+)?,-?\d+(?:\.\d+)?$/", $_GET["ne"]))
    {
        header("Content-type: text/plain");
        print(json_encode($cities));
        exit;
    }

    // get southwest corner
    list($lat, $lng) = split(",", $_GET["sw"]);
    $sw_lat = mysql_real_escape_string($lat);
    $sw_lng = mysql_real_escape_string($lng);

    // get northeast corner
    list($lat, $lng) = split(",", $_GET["ne"]);
    $ne_lat = mysql_real_escape_string($lat);
    $ne_lng = mysql_real_escape_string($lng);

    // find <= 5 largest cities within view
    if ($sw_lng > $ne_lng)
    {
        $sql = "SELECT ZipCode, SUM(Population) AS p, ZipCode, Latitude, Longitude, State, City " .
               "FROM " . TBL_NAME . " " .
               "WHERE $sw_lat <= Latitude AND Latitude <= $ne_lat " .
               "AND ($sw_lng <= Longitude OR Longitude <= $ne_lng) " .
               "GROUP BY City, State ORDER BY p DESC LIMIT 5";
    }
    else
    {
        $sql = "SELECT ZipCode, SUM(Population) AS p, ZipCode, Latitude, Longitude, State, City " .
               "FROM " . TBL_NAME . " " .
               "WHERE $sw_lat <= Latitude AND Latitude <= $ne_lat " .
               "AND $sw_lng <= Longitude AND Longitude <= $ne_lng " .
               "GROUP BY City, State ORDER BY p DESC LIMIT 5";
    }
    $result = mysql_query($sql);

    // iterate over result set
    while ($row = mysql_fetch_assoc($result))
    {
        // prepare an array for city
        $city = array();

        // populate array with fields from database
        $city["ZipCode"] = $row["ZipCode"];
        $city["City"] = ucwords(strtolower($row["City"]));
        $city["State"] = $row["State"];
        $city["Latitude"] = $row["Latitude"];
        $city["Longitude"] = $row["Longitude"];

        // prepare array for city's articles
        $city["articles"] = array();
        
        /*// to check if new file needed
        $cachetime = 5 * 60 * 60;
        $cachefile = "cache/" . $row['ZipCode'];
		if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
			include($cachefile);
		}
		else {
			// start output buffer
			ob_start();*/
	
			// fetch city's articles
			if ($rss = loadrss("http://news.google.com/news?geo={$row['ZipCode']}&output=rss", $row['ZipCode']))
			{
				foreach ($rss->channel->item as $item)
				{
					// prepare array for article
					$article = array();
	
					// populate array with data from RSS feed
					$article["link"] = (string) $item->link;
					$article["title"] = (string) $item->title;
	
					// associate article with city
					$city["articles"][] = $article;
				}
		
				// add city to array of cities
				$cities[] = $city;
			}
			

			/*$fp = fopen($cachefile, 'w');
			fwrite($fp, ob_get_contents());
			fclose($fp);
			ob_end_flush();
        }*/
    }

    // output cities
    header("Content-type: text/plain");
    print(json_encode($cities));
    
/*
     * void
     * loadrss($destination)
     * 
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any XHTML.
     */

    function loadrss($destination, $zip)
    {
    	$cachefile = "cache/" . $zip;
    	$cachetime = 5 * 60 * 60;
    	$rssreturn = null;
    	
    	if (file_exists($cachefile) && (time() - $cachetime < filemtime($cachefile))) {
			$rssreturn = @simplexml_load_file($cachefile);
			return $rssreturn;
		}
		else {
			copy($destination, $cachefile);
			$rssreturn = @simplexml_load_file($cachefile);
			return $rssreturn;
		}
			
	}

?>
