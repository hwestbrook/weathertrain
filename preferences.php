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
		<div id="container">
			<h2>weathertrain:preferences</h2><br />
			<div id="user-info-b"></div>
			<div id="weatherstation">
				<p>Weather Station: <span id="WS" class="edit_WS">No Data</span><span class="example"> example: MSFOC1 (click to edit text)</span></p>
			</div>
			<div id="firstbus">
				<p>First Agency: <span id="FA">No Data</span><span class="example"> example: sf-muni</span></p>
				<p>First Route: <span id="FR">No Data</span><span class="example"> example: N</span></p>
				<p>First Stop: <span id="FS">No Data</span><span class="example"> example: 4448</span></p>		
			</div>
			<div id="secondbus">
				<p>Second Agency: <span id="SA">No Data</span></p>
				<p>Second Route: <span id="SR">No Data</span></p>
				<p>Second Stop: <span id="SS">No Data</span></p>						
			</div>
			<div id="user">
				<a href="index.php"><div id="preferences"><span id="preferences-text">Home</span></div></a>
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

		      var userInfo = document.getElementById('user-info-b');
		      
					// get info from FB
					FB.api('/me', function(response) {
		        userInfo.innerHTML = '<img src="https://graph.facebook.com/' 
		      + response.id + '/picture">' + response.name;
		        button.innerHTML = 'Logout';
						uid = response.id;					
						
						// need to determine if this is a new user or not		
						if (checkUserNew(uid)) {
							// put something in her to write new user to MySQL
							newUserInput();
						}
						else {
							// run function to pull data from MySQL
							addBusAndWeather(uid);
						}
		      });

		      // logout button
					button.onclick = function() {
						FB.logout(function(response) {
		          var userInfo = document.getElementById('user-info-b');
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
								
								// get the info from FB
								FB.api('/me', function(response) {
		          		var userInfo = document.getElementById('user-info-b');
		          		userInfo.innerHTML = 
		                '<img src="https://graph.facebook.com/' 
		            	+ response.id + '/picture" style="margin-right:5px"/>' 
		            	+ response.name;
									uid = response.id;
		        		});
		    				
								// need to determine if this is a new user or not		
								if (checkUserNew(uid)) {
									// put something in her to write new user to MySQL
									newUserInput();
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

			if (weather_station === "") { $("#WS").replaceWith("<span id='WS' class='edit_WS'>click to enter</span>") } 
			else { $("#WS").replaceWith("<span id='WS' class='edit_WS'>"+weather_station+"</span>") };

			if (firstbus_agency === "") { $("#FA").replaceWith("<span id='FA' class='edit_FA'>click to enter</span>") } 
			else { $("#FA").replaceWith("<span id='FA' class='edit_FA'>"+firstbus_agency+"</span>") };

			if (firstbus_route === "") { $("#FR").replaceWith("<span id='FR' class='edit_FR'>click to enter</span>") } 
			else { $("#FR").replaceWith("<span id='FR' class='edit_FR'>"+firstbus_route+"</span>") };
			
			if (firstbus_stop === "") { $("#FS").replaceWith("<span id='FS' class='edit_FS'>click to enter</span>") } 
			else { $("#FS").replaceWith("<span id='FS' class='edit_FS'>"+firstbus_stop+"</span>") };
			
			if (secondbus_agency === "") { $("#SA").replaceWith("<span id='SA' class='edit_SA'>click to enter</span>") } 
			else { $("#SA").replaceWith("<span id='SA' class='edit_SA'>"+secondbus_agency+"</span>") };

			if (secondbus_route === "") { $("#SR").replaceWith("<span id='SR' class='edit_SR'>click to enter</span>") } 
			else { $("#SR").replaceWith("<span id='SR' class='edit_SR'>"+secondbus_route+"</span>") };
			
			if (secondbus_stop === "") { $("#SS").replaceWith("<span id='SS' class='edit_SS'>click to enter</span>") } 
			else { $("#SS").replaceWith("<span id='SS' class='edit_SS'>"+secondbus_stop+"</span>") };

			$(document).ready(function() {
				$('.edit_WS').editable('proxy_change-favorites.php?fb_uid=' + uid);
				$('.edit_FA').editable('proxy_change-favorites.php?fb_uid=' + uid);
				$('.edit_FR').editable('proxy_change-favorites.php?fb_uid=' + uid);
				$('.edit_FS').editable('proxy_change-favorites.php?fb_uid=' + uid);
				$('.edit_SA').editable('proxy_change-favorites.php?fb_uid=' + uid);
				$('.edit_SR').editable('proxy_change-favorites.php?fb_uid=' + uid);
				$('.edit_SS').editable('proxy_change-favorites.php?fb_uid=' + uid);
			 });

		};
		
		// returns true if user is new
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
				return false;
			}
			else {
				return true;
			}
			
		};
		
		// returns true if user is new
		function newUserInput() {
			
			// open connection to FB
			FB.api('/me', function(response) {
				// this pushes data to DB using proxy_user-data-push
				$.ajax({
				    type: "POST",
				    url: "proxy_user-data-push.php",
						data: {
							fb_uid: response.id,
							username: response.username,
							firstname: response.first_name,
							lastname: response.last_name,
							email: response.email
						},
				});
  		});
			
		};
		
		

		</script>
		
		<script>

		</script>
		
</body> 
</html>