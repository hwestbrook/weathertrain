<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">  
    <link rel="apple-touch-icon" href="images/template/engage.png"/>
	<title>weathertrain</title>
	<meta name="author" content="Heath Westbrook">
	<!-- Date: 2012-07-18 -->
	
	<!-- stylesheets -->
	<link href="assets/css/style.css" rel="stylesheet">
	
	<!-- standard JS -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>

</head>
<body>
	<div id="weather">
	</div>
	<div id="firstbus" class="businfo">
		N inbound</br>
	</div>
	<div id="secondbus" class="businfo">
		J inbound</br>
	</div>
	
	<script>
		
		var user_data = {};
		
		$.ajax({
			url: 'proxy_user-data.php',
			dataType: 'json',
			async: false,
			success: function(data) {
				user_data = data;
			}
		});
		
		// setup the variables for the weather and train stop information
		var weather_station = user_data[5];
		var firstbus_agency = user_data[6];
		var firstbus_route = user_data[7];
		var firstbus_stop = user_data[8];
		var secondbus_agency = user_data[9];
		var secondbus_route = user_data[10];
		var secondbus_stop = user_data[11];
		
		// this builds the URL for the weather station
		var weather_url = 'proxy_weather.php?weather_station=' + weather_station;
		
		// this calls the weatherunderground API and puts that data into the web page
		$.getJSON(weather_url, function(data) {

			$.each(data.forecast.txt_forecast.forecastday, function(i,item) {
				$('<h3>'+this.title+'</h3>'+'<p>'+this.fcttext+'</p>',this).appendTo("#weather");
			});

		});
		
		// this calls the nextmuni API for the XML file
		$.ajax({
		    type: "GET",
		    url: "proxy_commute.php",
			data: {
				agency: firstbus_agency,
				route: firstbus_route,
				stop: firstbus_stop
			},
		    dataType: "xml",
		    success: parseXMLfirstbus
		});

		
		function parseXMLfirstbus(xml)
		{
		  //find every prediction and print the time
		  $(xml).find("prediction").each(function()
		  {
		    $("#firstbus").append($(this).attr("minutes") + " minutes<br />");
		  });

		}
		
		// this calls the nextmuni API for the XML file
		
		$.ajax({
	    	type: "GET",
	    	url: "proxy_commute.php",
			data: {
				agency: secondbus_agency,
				route: secondbus_route,
				stop: secondbus_stop
			},
	    	dataType: "xml",
	    	success: parseXMLsecondbus
		});
		
		function parseXMLsecondbus(xml)
		{
		  //find every prediction and print the time
		  $(xml).find("prediction").each(function()
		  {
		    $("#secondbus").append($(this).attr("minutes") + " minutes <br />");
		  });

		}
		
	</script>
	
</body>
</html>
