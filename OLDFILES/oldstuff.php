<form>
	<div id="firstbus">
		<p>First Agency: 
			<select name="firstbus" id="firstbus_select">
				<?php
				while($row = mysql_fetch_array($agencyList)) {
					echo "<option value='" . $row['short_name'] . "'>" . $row['name'] . "</option>";
				}
				?>
			</select>
		</p>
		<p>First Route: 
			<select name="firstroute" id="firstroute_select">
				<option value=""> -- please choose agency first -- </option>
			</select>
		</p>
		<p>First Stop: 
			<select name="firststop" id="firststop_select">
				<option value=""> -- please choose route first -- </option>
			</select>
		</p>	
	</div>
</form>


<form>
	<div id="secondbus">
		<p>Second Agency: 
			<select name="secondbus" id="secondbus_select">
				<?php
				// reset the position
				mysql_data_seek($agencyList, 0);

				while($row2 = mysql_fetch_array($agencyList)) {
					echo "<option value='" . $row2['short_name'] . "'>" . $row2['name'] . "</option>";
				}
				?>
			</select>
		</p>
		<p>Second Route: 
			<select name="secondroute" id="secondroute_select">
				<option value=""> -- please choose agency first -- </option>
			</select>
		</p>
		<p>Second Stop: 
			<select name="secondstop" id="secondstop_select">
				<option value=""> -- please choose route first -- </option>
			</select>
		</p>					
	</div>
</form>


var firstbus_agency = user_data[6];
var firstbus_route = user_data[7];
var firstbus_stop = user_data[8];
var secondbus_agency = user_data[9];
var secondbus_route = user_data[10];
var secondbus_stop = user_data[11];


// selects a value in the select forms using JS
function selectItemByValue(elmnt, value){
  for(var i=0; i < elmnt.options.length; i++)
  {
    if(elmnt.options[i].value == value)
      elmnt.selectedIndex = i;
  }
}

var fb_agency = document.getElementById('firstbus_select');
var fb_route = document.getElementById('firstroute_select');
var fb_stop = document.getElementById('firststop_select');
var sb_agency = document.getElementById('secondbus_select');
var sb_route = document.getElementById('secondroute_select');
var sb_stop = document.getElementById('secondstop_select');

// to update the routes selected		
function firstroute_selectbox_change(){
	$(fb_agency).change(update_route_list(fb_agency, fb_route));
}
// function secondroute_selectbox_change(){
// 			$('#secondbus_select').change(update_route_list('#secondbus_select'));
// 		}

// to update the stops selected
// function firststop_selectbox_change(){
// 			$('#firstroute_select').change(update_stop_list('#firstbus_select' , '#firstroute_select'));
// 		}
// 		function secondstop_selectbox_change(){
// 			$('#secondroute_select').change(update_stop_list('#secondbus_select' , '#secondroute_select'));
// 		}

function update_route_list(agencySelect, routeSelect){
	var agency=$(agencySelect).attr('value');
	$.get('proxy_get-routes.php?agency='+ agency, show_routes(routeSelect));
}

// function update_stop_list(agencySelect , routeSelect){
// 			var agency=$(agencySelect).attr('value');
// 			var route=$(routeSelect).attr('value');
// 			$.get('proxy_get-stops.php?agency=' + agency + '&route=' + route, show_stops(routeSelect));
// 		}

function show_routes(routeSelect , firstroute){
	$(routeSelect).html(firstroute);
}

// function show_stops(route, routeSelect){
// 			$(routeSelect).html(route);
// 		}
