<!DOCTYPE html> 
<html xmlns:fb="https://www.facebook.com/2008/fbml">
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">  
	    <link rel="apple-touch-icon" href="images/template/engage.png"/>
		<title>weathertrain:preferences</title>
		<meta name="author" content="Heath Westbrook">
		<!-- Date: 2012-07-18 -->

		<!-- stylesheets -->
		<link href="assets/css/style.css" rel="stylesheet">

		<!-- standard JS -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
	  <script src="assets/js/jquery.jeditable.mini.js"></script>
  </head> 
<body> 
    
	<div id="fb-root"></div>
	<h2>weathertrain:preferences</h2><br />
	<div id="user-info"></div>
	<div id="weatherstation">
		<p id="weatherstationrewrite">No Data</p>
	</div>
	<div id="firstbus">
		<p id="firstbusrewrite">No Data</p>
	</div>
	<div id="secondbus">
		<p id="secondbusrewrite">No Data</p>		
	</div>
	<p><button id="fb-auth">Login</button></p>

		<script>
		window.fbAsyncInit = function() {
		  
			var username;
		
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
		        button.innerHTML = 'Logout';
						
						// run function to pull data from MySQL
						addBusAndWeather(response.id);
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
		      button.innerHTML = 'Login';
		      button.onclick = function() {
		        FB.login(function(response) {
		      		if (response.authResponse) {
		            
								var uid;
								
								// get the info from FB
								FB.api('/me', function(response) {
		          		var userInfo = document.getElementById('user-info');
		          		userInfo.innerHTML = 
		                '<img src="https://graph.facebook.com/' 
		            	+ response.id + '/picture" style="margin-right:5px"/>' 
		            	+ response.name;
									uid = response.id;
		        		});
		    				
								// need to determine if this is a new user or not		
								if (checkUserNew(uid)) {
									// put something in her to write new user to MySQL
									
								}
								else {
									// run function to pull data from MySQL
									addBusAndWeather(uid);
								}
		
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

		function addBusAndWeather(uid) {
			
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
			
			$("#weatherstationrewrite").replaceWith(weather_station);
			$("#firstbusrewrite").replaceWith("Agency: " + firstbus_agency + ", Route: " + firstbus_route + ", Stop: " + firstbus_stop);
			$("#secondbusrewrite").replaceWith("Agency: " + secondbus_agency + ", Route: " + secondbus_route + ", Stop: " + secondbus_stop);
						
		};
		
		function checkUserNew(uid) {
			var user_data = {};
		
			$.ajax({
				url: 'proxy_user-data.php?fb_uid=' + uid,
				dataType: 'json',
				async: false,
				success: function(data) {
					user_data = data;
				}
			});
			
			var user_id = user_data[0];
			
			if (user_id === uid) {
				return true;
			}
			else {
				return false;
			}
			
		};
		
		

		</script>
		
		<script>
			$(document).ready(function() {
				$('.edit_1').editable('save.php');
				$('.edit_2').editable('save.php');
				$('.edit_3').editable('save.php');
			 });
		</script>
		
</body> 
</html>