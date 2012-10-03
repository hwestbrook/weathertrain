<!DOCTYPE html> 
<html xmlns:fb="https://www.facebook.com/2008/fbml">
  <head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">  
	    <link rel="apple-touch-icon" href="images/template/engage.png"/>
		<title>Weathertrain: Preferences</title>
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
	
		<!-- basic PHP -->
		<?php
			// this disallows cross site attacks
			// header('Access-Control-Allow-Origin: *');

		  //--------------------------------------------------------------------------
		  // 1) Connect to mysql database
		  //--------------------------------------------------------------------------
		  include 'ajax/DB.php';
		  $con = mysql_connect($host,$user,$pass);
		  $dbs = mysql_select_db($databaseName, $con);
		
			//--------------------------------------------------------------------------
		  // 2) Get the list of agencies from our database
		  //--------------------------------------------------------------------------

		  $agencyQuery = "SELECT `short_name` , `name` FROM `hwestbro_weathertrain`.`muni-agencies`";

			// run the query
			$agencyList = mysql_query($agencyQuery);
			
		?>
		
  </head> 
<body> 
    
	<div id="fb-root"></div>

	<div class="navbar navbar-fixed-top navbar-inverse">
    <div class="navbar-inner">
      <div class="container">
				<div class="welcomeUser"><span id="user-info-b"></span></div>
				<a href="index.php" class="brand">Home</a>
				<div id="fb-auth" class="brand"><span id="fb-auth-text">Login</span></div>
      </div>
    </div>
  </div>
		
	<div class="container">
		<div class="row">
			<div class="span12">
				
				<ul id="myTab" class="nav nav-pills">
	        <li class="active"><a href="#profile" data-toggle="tab">Profile</a></li>
	        <li><a href="#weather" id="tab_map_link" data-toggle="tab">Weather</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Transit <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	            <li><a href="#transit1" data-toggle="tab">Transit 1</a></li>
	            <li><a href="#transit2" data-toggle="tab">Transit 2</a></li>
	          </ul>
	        </li>
	      </ul>

	      <div id="myTabContent" class="tab-content">

					<!-- profile -->
	        <div class="tab-pane fade in active" id="profile">
	          No profile information right now.
	        </div>
				
					<!-- weather -->
	        <div class="tab-pane fade" id="weather">
						<div id="weatherstation">
							<p>Weather Station: <span id="WS" class="edit_WS">No Data</span><span class="example"> example: MSFOC1 (click to edit text)</span></p>
							<div>
					      <input id="searchTextField" type="text" size="50">
					      <input type="radio" name="type" id="changetype-all" checked="checked">
					      <label for="changetype-all">All</label>

					      <input type="radio" name="type" id="changetype-establishment">
					      <label for="changetype-establishment">Establishments</label>

					      <input type="radio" name="type" id="changetype-geocode">
					      <label for="changetype-geocode">Geocodes</lable>
					    </div>
					    <div id="map_canvas"></div>
						</div>
	        </div>
				
					<!-- transit 1 -->
	        <div class="tab-pane fade" id="transit1">
	          <form action="ajax/pushFavorites.php" id="firstbus_form" method="get" accept-charset="utf-8">
	          	
							<input type="hidden" id="fb_uid1" name="uid" value="">
							<input type="hidden" name="transit" value="firstbus">
							<select name="firstbus_list1" id="firstbus_list1"></select> 
							<select name="firstbus_list2" id="firstbus_list2"></select>
							<select name="firstbus_list3" id="firstbus_list3"></select>

	          	<p><input type="submit" value="Update &rarr;"><span id="transit1_success"></span></p>
	          </form>
	        </div>

					<!-- transit 2 -->
	        <div class="tab-pane fade" id="transit2">
	          <form action="ajax/pushFavorites.php" id="secondbus_form" method="get" accept-charset="utf-8">
	          	
							<input type="hidden" id="fb_uid2" name="uid" value="">
							<input type="hidden" name="transit" value="secondbus">
							<select name="secondbus_list1" id="secondbus_list1"></select> 
							<select name="secondbus_list2" id="secondbus_list2"></select>
							<select name="secondbus_list3" id="secondbus_list3"></select>

	          	<p><input type="submit" value="Update &rarr;"><span id="transit2_success"></span></p>
	          </form>
	        </div>
	      </div> 	<!-- myTabContent -->
		
			</div> <!-- span -->
		</div> <!-- row -->
	</div> <!-- container -->
		

		<!-- Le javascript
	  ================================================== -->
	  <!-- Placed at the end of the document so the pages load faster -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.21/jquery-ui.min.js"></script>
	  <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.0.4/js/bootstrap.min.js"></script>
		<script src="assets/js/jquery.jCombo.min.js"></script>


		<!-- page JS -->

		<script>
			var uid;
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

		      var userInfo = document.getElementById('user-info-b');
		      
					// get info from FB
					FB.api('/me', function(response) {
		        userInfo.innerHTML = 'Logged in as ' + response.name;
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

			// this puts the weather data into the right place for the firstbus
			if (weather_station === "") { $("#WS").replaceWith("<span id='WS' class='edit_WS'>click to enter</span>") } 
			else { $("#WS").replaceWith("<span id='WS' class='edit_WS'>"+weather_station+"</span>") };

			// input the uid into the forms below
			$('#fb_uid1').val(uid);
			$('#fb_uid2').val(uid);
			
			// this creates the firstbus selectors
			$("#firstbus_list1").jCombo("ajax/getAgencies.php", { selected_value : firstbus_agency } );
			$("#firstbus_list2").jCombo("ajax/getRoutes.php?id=", { 
							parent: "#firstbus_list1", 
							parent_value: firstbus_agency, 
							selected_value: firstbus_route 
			});		
		  $("#firstbus_list3").jCombo("ajax/getStops.php?id=", { 
							parent: "#firstbus_list2", 
							parent_value: firstbus_route, 
							selected_value: firstbus_stop 
			});
			
			// this creates the secondbus selectors
			$("#secondbus_list1").jCombo("ajax/getAgencies.php", { selected_value : secondbus_agency } );
			$("#secondbus_list2").jCombo("ajax/getRoutes.php?id=", { 
							parent: "#secondbus_list1", 
							parent_value: secondbus_agency, 
							selected_value: secondbus_route 
			});		
		  $("#secondbus_list3").jCombo("ajax/getStops.php?id=", { 
							parent: "#secondbus_list2", 
							parent_value: secondbus_route, 
							selected_value: secondbus_stop 
			});

		};
		
		// returns true if user is new
		function checkUserNew(uid) {
			var user_data = {};
		
			$.ajax({
				url: 'ajax/getUserData.php?fb_uid=' + uid,
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
				// this pushes data to DB using ajax/pushNewUser
				$.ajax({
				    type: "POST",
				    url: "ajax/pushNewUser.php",
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

		/* attach a submit handler to the form */
	  $("#firstbus").submit(function(event) {

	    /* stop form from submitting normally */
	    event.preventDefault(); 

	    /* get some values from elements on the page: */
	    var $form = $( this ),
					fb_uid = uid,
	        transit = $form.find( 'input[name="transit"]' ).val(),
					agency = $form.find( 'input[name="firstbus_list1"]' ).val(),
					route = $form.find( 'input[name="firstbus_list2"]' ).val(),
					stop = $form.find( 'input[name="firstbus_list3"]' ).val(),
	        url = $form.attr( 'action' );

	    /* Send the data using post and put the results in a div */
	    $.post( url, { uid: fb_uid, t: transit, a: agency, r: route, s: stop },
	      function( data ) {
	          var content = $( data ).find( '#content' );
	          $( "#result1" ).empty().append( content );
	      }
	    );
	  });

		$("#firstbus_form").submit(function(event) {

	    /* stop form from submitting normally */
	    event.preventDefault(); 

			$.post("ajax/pushFavorites.php", $("#firstbus_form").serialize(),
				function( data ) {
					$( "#transit1_success" ).empty().append( data );
					$(".label-success").delay(2000).fadeOut("slow", function () { $(this).remove(); });					
				}
			);

	  });
	
		$("#secondbus_form").submit(function(event) {

	    /* stop form from submitting normally */
	    event.preventDefault(); 

			$.post("ajax/pushFavorites.php", $("#secondbus_form").serialize(),
				function( data ) {
					$( "#transit2_success" ).empty().append( data );
					$(".label-success").delay(2000).fadeOut("slow", function () { $(this).remove(); });
				}
			);

	  });
	
		</script>
		
		<!-- google maps stuff -->
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
    <script>
	    
			function weatherMap() {
	      var mapOptions = {
	        center: new google.maps.LatLng(-33.8688, 151.2195),
	        zoom: 13,
	        mapTypeId: google.maps.MapTypeId.ROADMAP
	      };

	      var map = new google.maps.Map(document.getElementById('map_canvas'),
			        mapOptions);

	      var input = document.getElementById('searchTextField');
	      var autocomplete = new google.maps.places.Autocomplete(input);

	      autocomplete.bindTo('bounds', map);

	      var infowindow = new google.maps.InfoWindow();
	      var marker = new google.maps.Marker({
	        map: map
	      });

	      google.maps.event.addListener(autocomplete, 'place_changed', function() {
	        infowindow.close();
	        var place = autocomplete.getPlace();
	        if (place.geometry.viewport) {
	          map.fitBounds(place.geometry.viewport);
	        } else {
	          map.setCenter(place.geometry.location);
	          map.setZoom(17);  // Why 17? Because it looks good.
	        }

	        var image = new google.maps.MarkerImage(
	            place.icon,
	            new google.maps.Size(71, 71),
	            new google.maps.Point(0, 0),
	            new google.maps.Point(17, 34),
	            new google.maps.Size(35, 35));
	        marker.setIcon(image);
	        marker.setPosition(place.geometry.location);

	        var address = '';
	        if (place.address_components) {
	          address = [
	            (place.address_components[0] && place.address_components[0].short_name || ''),
	            (place.address_components[1] && place.address_components[1].short_name || ''),
	            (place.address_components[2] && place.address_components[2].short_name || '')
	          ].join(' ');
	        }

	        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
	        infowindow.open(map, marker);
	      });

	      // Sets a listener on a radio button to change the filter type on Places
	      // Autocomplete.
	      function setupClickListener(id, types) {
	        var radioButton = document.getElementById(id);
	        google.maps.event.addDomListener(radioButton, 'click', function() {
	          autocomplete.setTypes(types);
	        });
	      }

	      setupClickListener('changetype-all', []);
	      setupClickListener('changetype-establishment', ['establishment']);
	      setupClickListener('changetype-geocode', ['geocode']);
	    }
	
			$('#tab_map_link').on('show', function(e) {
					weatherMap();
			});
	  </script>

</body> 
</html>