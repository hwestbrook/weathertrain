/*
 * mashup.js
 *
 * Computer Science 50
 * Problem Set 8
 *
 * Implements weathertrain.
 */


// // mashup's geocoder
// var geocoder = new GClientGeocoder;

// // map's home 
// var home = new GLatLng(37.336725, -121.950877);

// // mashup's Google Map
// var map = null;


/*
 * void
 * addMarker(point, xhtml)
 *
 * Adds marker with given xhtml at given point on map.
 */

// function addMarker(point, xhtml)
// {
//     // instantiate marker
//     var marker = new GMarker(point);
// 
//     // prepare info window for city
//     GEvent.addListener(marker, "click", function() {
//         //map.openInfoWindowHtml(point, xhtml);
//         document.getElementById("map").style.top = 200 + "px";
//         var height = window.innerHeight - 100 - 150;
//         document.getElementById("map").style.height = height + "px";
//         document.getElementById("newsinfo").innerHTML = xhtml;
//         
//     });
// 
//     // add marker to map
//     map.addOverlay(marker);
// }


/*
 * void
 * go()
 *
 * Pans map to desired location if possible, else yells at user.
 */

// function showAddress(address) {
//   if (geocoder) {
// 	geocoder.getLatLng(
// 	  address,
// 	  function(point) {
// 		if (!point) {
// 		  alert(address + " not found");
// 		} 
// 		else {
// 		  map.setCenter(point, 10);
// 		  var marker = new GMarker(point);
// 		}
// 	  }
// 	 );
//   }
// }


/*
 * void
 * load()
 *
 * Loads (and configures) Google Map.
 */

function load() 
{
    // // ensure browser supports Google Maps
    // if (!GBrowserIsCompatible())
    //     return;
    // 
    // // instantiate geocoder
    // geocoder = new GClientGeocoder();
    // 
    // // resize map's container
    // resize();
    // 
    // // instantiate map
    // map = new GMap2(document.getElementById("map"));
    // 
    // // center map on home
    // map.setCenter(home, 10);
    // 
    // // TODO: add control(s)
    // map.addControl(new GHierarchicalMapTypeControl());
    // map.addControl(new GSmallZoomControl3D());
    // 
    // // update markers anytime user drags or zooms map
    // GEvent.addListener(map, "dragend", update);
    // GEvent.addListener(map, "zoomend", update);
    // 
    // // resize map anytime user resizes window
    // GEvent.addDomListener(window, "resize", resize);
    // 
    // // update markers
    // update();
    // 
    // // give focus to text field
    // document.getElementById("q").focus();
}


/*
 * void
 * resize()
 *
 * Resizes map's container to fill area below form.
 */

// function resize()
// {
//     // prepare to determine map's height
//     var height = 0;
// 
//     // check for non-IE browsers
//     if (window.innerHeight !== undefined)
//         height += window.innerHeight;
// 
//     // check for IE
//     else if (document.body.clientHeight !== undefined)
//         height += document.body.clientHeight;
// 
//     // leave room for logo and form
//     height -= 110;
// 
//     // maximize map's height if room
//     if (height > 0)
//     {
//         // adjust height via CSS
//         document.getElementById("map").style.height = height + "px";
// 
//         // ensure map exists
//         if (map)
//         {
//             // resize map
//             map.checkResize();
// 
//             // update markers
//             update();
//         }
//     }
//     
//     var width = window.innerWidth;
//     document.getElementById("newsinfo").style.width = width - 28 + "px";
// }


/*
 * void
 * unload()
 *
 * Unloads Google Map.
 */

function unload()
{
    // unload Google's API
    GUnload();
}


/*
 * void
 * update()
 *
 * Updates map with markers for largest <= 5 cities within view.
 * Also displays marker for home if within view.
 */

// function update()
// {
//     // clear any existing markers
//     map.clearOverlays();
//     document.getElementById("map").style.top = 60 + "px";
//     var height = window.innerHeight - 110;
//     document.getElementById("map").style.height = height + "px";
// 
// 	// show progress
//     document.getElementById("progress").style.display = "block";
// 
//     // get map's bounds
//     var bounds = map.getBounds();
//     var southWest = bounds.getSouthWest();
//     var northEast = bounds.getNorthEast();
// 
//     // mark home if within bounds
//     if (bounds.containsLatLng(home))
//     {
//         // prepare XHTML
//         var xhtml = "<b>Home Sweet Home</b>";
// 
//         // add marker to map
//         addMarker(home, xhtml);
//     }
// 
//     // contact proxy, send the bounds
//     proxycontact(southWest, northEast);
// }

/*
 * void
 * proxycontact()
 *
 * contacts proxy.php for JSON
 * 
 */

function proxycontact(sw, ne)
{	
	// instantiate XMLHttpRequest object
	try
	{
		xhr = new XMLHttpRequest();
	}
	catch (e)
	{
		xhr = new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	// handle old browsers
	if (xhr == null)
	{
		alert("Ajax not supported by your browser!");
		return;
	}
	
	// construct URL
	var url = "proxy.php?sw=" + sw.lat() + "," + sw.lng() + "&ne=" + ne.lat() + "," + ne.lng();
	
	// get the data back from proxy
	xhr.onreadystatechange = function() {
	
		// only handle requests in "loaded" state
		if (xhr.readyState == 4)
		{
			// embed response in page if possible
			if (xhr.status == 200)
			{
				// hide progress
    			document.getElementById("progress").style.display = "none";
    
				// to evaluate news feed
				// example of how to parse ...
				// document.getElementById("checker").innerHTML = newslocs[1]["articles"][1]["title"];
				var newslocs = eval( xhr.responseText );

				// loop through the 5 cities
				for (var i = 0; i < 5; i++) {
					
					// first do the xhtml
					var xhtml = "";
					for (var j = 0; j < 10; j++) {
						// put the title into xhtml
						xhtml += "<a href=" + newslocs[i]["articles"][j]["link"] + ">" + newslocs[i]["articles"][j]["title"] + "</a>";
						xhtml += "<br />";
					}
					
					// make the point next
					var Lat = parseFloat(newslocs[i]["Latitude"]);
					var Long = parseFloat(newslocs[i]["Longitude"]);
					var point = new GLatLng(Lat, Long);

					// pass the values to addMarker
					addMarker(point, xhtml);
				}
			}
			else
				alert("Error with Ajax call!");
		}
	
	};
	xhr.open("GET", url, true);
	xhr.send(null);
}
