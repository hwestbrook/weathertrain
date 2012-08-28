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
	<div id="fb-root"></div>
	<div id="container">
		<div id="weather"></div>
		<div id="firstbus" class="businfo">
		</div>
		<div id="secondbus" class="businfo">
		</div>
		<div id="user">
			<a href="preferences.php"><div id="preferences"><span id="preferences-text">Preferences</span></div></a>
			<span id="user-info"></span>
			<div id="fb-auth"><span id="fb-auth-text">Login</span></div>
		</div>
	</div>
	
	<script>
		
		window.fbAsyncInit = function() {
		  
			var username;
			var uid;
		
			FB.init({ 
						appId: '398916900171040', 
						channelUrl : '//www.hiro-o.net/weathertrain/channel.html', // Channel File
		        status: true, 
		        cookie: true,
		        xfbml: true,
		        oauth: true});

		  function updateButton(response) {
		    var button = document.getElementById('fb-auth');
        

		    //user is already logged in and connected
		    if (response.authResponse) {

		      var userInfo = document.getElementById('user-info');
		      
					// get info from FB
					FB.api('/me', function(response) {
		        userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
		      + response.id + '/picture">' + response.name;
		        button.innerHTML = "<span id='fb-auth-text'>Logout</span>";
						uid = response.id;
						userDataAndWeather(uid);					
		      });

		      // logout button
					button.onclick = function() {
						FB.logout(function(response) {
		          var userInfo = document.getElementById('user-info');
		          userInfo.innerHTML="";
		    		});
		    	};
		    } 
				else {
		      //user is not connected to your app or logged out
		      button.innerHTML = "<span id='fb-auth-text'>Login</span>";
		      button.onclick = function() {
		        FB.login(function(response) {
		      		if (response.authResponse) {
								
								// get the info from FB
								FB.api('/me', function(response) {
		          		var userInfo = document.getElementById('user-info');
		          		userInfo.innerHTML = 
		                '<img src="https://graph.facebook.com/' 
		            	+ response.id + '/picture" style="margin-right:5px"/>' 
		            	+ response.name;
									uid = response.id;
									userDataAndWeather(uid);
		        		});
		      		} 
							else {
		            //user cancelled login or did not grant authorization
		        	}
		      	}, {scope:'email'});    
		    	}
		  	}
			}

		  // run once with current status and whenever the status changes
		  FB.getLoginStatus(updateButton);
		  FB.Event.subscribe('auth.statusChange', updateButton);
		};
		
		(function() {
		  var e = document.createElement('script'); e.async = true;
		  e.src = document.location.protocol 
		    + '//connect.facebook.net/en_US/all.js';
		  document.getElementById('fb-root').appendChild(e);
		}());
		
		function userDataAndWeather(uid) {
			var user_data = {};

			$.ajax({
				url: 'proxy_user-data.php?fb_uid=' + uid,
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
				
				$("#weather").replaceWith("<div id='weather'></div>");
				
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
			  $("#firstbus").replaceWith("<div id='firstbus' class='businfo'></div>");
				$(xml).find("predictions").each(function()
			  {
			    $("#firstbus").append($(this).attr("routeTitle") + "<br />");
			  });
			

				//find every prediction and print the time, but limit to 4 results
				var i = 0;				
			  $(xml).find("prediction").each(function()
			  {
					i++;
					$("#firstbus").append($(this).attr("minutes") + " minutes<br />");
					if (i >= 4) { return false; };
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
				$("#secondbus").replaceWith("<div id='secondbus' class='businfo'></div>");
				$(xml).find("predictions").each(function()
			  {
			    $("#secondbus").append($(this).attr("routeTitle") + "<br />");
			  });
				//find every prediction and print the time, but limit to 4 results
				var i = 0;
			  $(xml).find("prediction").each(function()
			  {
					i++;
			    $("#secondbus").append($(this).attr("minutes") + " minutes <br />");
					if (i >= 4) { return false; };					
			  });

			}
		}
		
	</script>
	
</body>
</html>
