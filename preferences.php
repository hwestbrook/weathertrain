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
        
		    if (response.authResponse) {
		      //user is already logged in and connected
		      var userInfo = document.getElementById('user-info');
		      FB.api('/me', function(response) {
		        userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
		      + response.id + '/picture">' + response.name;
		        button.innerHTML = 'Logout';
		      });
		      button.onclick = function() {
		        FB.logout(function(response) {
		          var userInfo = document.getElementById('user-info');
		          userInfo.innerHTML="";
		    });
		      };
		    } else {
		      //user is not connected to your app or logged out
		      button.innerHTML = 'Login';
		      button.onclick = function() {
		        FB.login(function(response) {
		      if (response.authResponse) {
		            FB.api('/me', function(response) {
		          var userInfo = document.getElementById('user-info');
		          userInfo.innerHTML = 
		                '<img src="https://graph.facebook.com/' 
		            + response.id + '/picture" style="margin-right:5px"/>' 
		            + response.name;
		        });    
		          } else {
		            //user cancelled login or did not grant authorization
		          }
		        }, {scope:'email'});    
		      }
		    }
				FB.api('/me', function(response) {
					addBusAndWeather(response.username);
	      });
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

		function addBusAndWeather(username) {
			
			var user_data = {};
		
			$.ajax({
				url: 'proxy_user-data.php?username=' + username,
				dataType: 'json',
				async: false,
				success: function(data) {
					user_data = data;
				}
			});
		
			// setup the variables for the weather and train stop information
			var weather_station = user_data[4];
			var firstbus_agency = user_data[5];
			var firstbus_route = user_data[6];
			var firstbus_stop = user_data[7];
			var secondbus_agency = user_data[8];
			var secondbus_route = user_data[9];
			var secondbus_stop = user_data[10];
			
			$("#weatherstationrewrite").replaceWith(weather_station);
			$("#firstbusrewrite").replaceWith("Agency: " + firstbus_agency + ", Route: " + firstbus_route + ", Stop: " + firstbus_stop);
			$("#secondbusrewrite").replaceWith("Agency: " + secondbus_agency + ", Route: " + secondbus_route + ", Stop: " + secondbus_stop);
						
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