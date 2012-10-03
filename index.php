<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">  
    <link rel="apple-touch-icon" href="images/template/engage.png"/>
	<title>Weathertrain</title>
	<meta name="author" content="Heath Westbrook">
	<!-- Date: 2012-07-18 -->
	
	<!-- Le styles -->
  <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/css/bootstrap-combined.min.css" rel="stylesheet">
	<link href="assets/css/style.css" rel="stylesheet">

  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!-- Le fav and touch icons -->
  <link rel="shortcut icon" href="assets/ico/favicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">

</head>
<body>
	<div id="fb-root"></div>

	<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
      <div class="container">
				<div class="welcomeUser"><span id="user-info"></span></div>
				<a href="preferences.php" class="brand">Preferences</a>
				<div id="fb-auth" class="brand"><span id="fb-auth-text">Login</span></div>
      </div>
    </div>
  </div>

	<div class="content">
		<div class="container">
			<div class="row">
				<div class="span5 relative">
					<div class="weather">
						<div id="weather"></div>
						<hr />
						<button type="button" class="btn btn-danger space" data-toggle="collapse" data-target="#weather_extra">
						  Additional Weather
						</button>
						<div id="weather_extra" class="collapse"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span5 relative">
					<div class="bus">
						<div id="firstbus" class="businfo"></div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span5 relative">
					<div class="bus">
						<div id="secondbus" class="businfo"></div>
					</div>
				</div>
			</div> <!-- row -->
		</div> <!-- container -->
	</div> <!-- content -->
		
	<div class="gradient"></div>

	<div class="footer">
		<div class="container">
			<div class="row">

			</div> <!-- row -->
		</div> <!-- container -->
	</div> <!-- footer -->

	

	
	<!-- Le javascript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap.min.js"></script>
  
	
	<!-- page JS -->
	
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
		        userInfo.innerHTML = 'Logged in as ' + response.name;
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
				url: 'ajax/getUserData.php?fb_uid=' + uid,
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
			var weather_url = 'ajax/getWeather.php?weather_station=' + weather_station;

			// this calls the weatherunderground API and puts that data into the web page
			$.getJSON(weather_url, function(data) {
				
				$("#weather").replaceWith("<div id='weather'></div>");
				$("#weather_extra").replaceWith("<div id='weather_extra' class='collapse'></div>");
				
				var k = 0;
				
				$.each(data.forecast.txt_forecast.forecastday, function(i,item) {
					if (k == 1) { return false; };
					$('<h3>'+this.title+'</h3>'+'<p>'+this.fcttext+'</p>',this).appendTo("#weather");
					k++;
				});
				
				l = 0;
				
				$.each(data.forecast.txt_forecast.forecastday, function(i,item) {
					if (l == 0) { 
						l++;
						return true; 						
					};
					$('<h3>'+this.title+'</h3>'+'<p>'+this.fcttext+'</p>',this).appendTo("#weather_extra");
					l++;
				});

			});

			// this calls the nextmuni API for the XML file
			$.ajax({
			    type: "GET",
			    url: "ajax/getMuniTimes.php",
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
			    $("#firstbus").append("<h3>" + $(this).attr("routeTitle") + "</h3>");
			  });
			

				// find every predicted time, limited to 4 results, put into an array
				var i = 0;
				var timeArray = new Array();
			  $(xml).find("prediction").each(function()
			  {
			    timeArray[i] = parseInt($(this).attr("minutes"));
					i++;
					if (i >= 4) { return false; };					
			  });
			
				// sort the array
				timeArray.sort(function(a,b){return a-b});
				
				// put the sorted times into the HTML
				var j = 0;
				$(timeArray).each(function()
			  {
			    $("#firstbus").append("<p>" + timeArray[j] + " minutes </p>");
					j++;
			  });

			}

			// this calls the nextmuni API for the XML file

			$.ajax({
		    	type: "GET",
		    	url: "ajax/getMuniTimes.php",
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
			    $("#secondbus").append("<h3>" + $(this).attr("routeTitle") + "</h3>");
			  });
			
				// find every predicted time, limited to 4 results, put into an array
				var i = 0;
				var timeArray = new Array();
			  $(xml).find("prediction").each(function()
			  {
			    timeArray[i] = parseInt($(this).attr("minutes"));
					i++;
					if (i >= 4) { return false; };					
			  });
			
				// sort the array
				timeArray.sort(function(a,b){return a-b});
				
				// put the sorted times into the HTML
				var j = 0;
				$(timeArray).each(function()
			  {
			    $("#secondbus").append("<p>" + timeArray[j] + " minutes </p>");
					j++;
			  });
			}
		}
		
	</script>
	
</body>
</html>
