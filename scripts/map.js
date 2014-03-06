// Map stuff
var map;
var InfoWindow = new google.maps.InfoWindow();
var userinfo = [];
var markers = [];
var myPosition;
var mydest;
var centerOn;
var jsSnearby = [];
var dest;

// Map route vars
var bounds = new google.maps.LatLngBounds();

// Map functions
function nearby(range, callback){

	if(jsSlat != 0){
		var mylatsub = jsSlat - range;
		var mylatadd = jsSlat + range;
		var mylngsub = jsSlng - range;
		var mylngadd = jsSlng + range;

		var get = "username, latitude, longitude, type";
		var conditions = "latitude BETWEEN "+mylatsub+" AND "+mylatadd+" AND longitude BETWEEN "+mylngsub+" AND "+mylngadd+" AND NOT username = '"+jsSusername+"'";
		}
	if(jsSlngd != 0){
		var mylatdsub = jsSlatd - range;
		var mylatdadd = jsSlatd + range;
		var mylngdsub = jsSlngd - range;
		var mylngdadd = jsSlngd + range;
		var get = "username, latitude, longitude, type, dlatitude, dlongitude, spots, availablespots, latestleave, rleave1, rleave2, days";
		var conditions = "latitude BETWEEN "+mylatsub+" AND "+mylatadd+" AND longitude BETWEEN "+mylngsub+" AND "+mylngadd+" AND dlatitude BETWEEN "+mylatdsub+" AND "+mylatdadd+" AND dlongitude BETWEEN "+mylngdsub+" AND "+mylngdadd+" AND NOT username = '"+jsSusername+"'";
		}
	if((jsSlat == 0)&&(jsSlngd == 0)) console.log("Error in nearby(); jsSlat"+jsSlat+" jsSlngd"+jsSlngd);

	getFromTable("carpool_members", get, conditions, function(data){
		if(data === "none") nearby(range+0.05, callback);
		else {		
			 if (typeof callback=="function") callback(JSON.parse(data));
			}
		});
	}

function setAllMap(map) {
	for (var i = 0; i < markers.length; i++) {
		markers[i].setMap(map);
		}
	}

// Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
	setAllMap(null);
	}

// Shows any markers currently in the array.
function showMarkers() {
	setAllMap(map);
	}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
	clearMarkers();
	markers = [];
	}

function initMap(centerOn,zoomval,divId, callback){
	map = false;
	var mapOptions = {
 		zoom: zoomval,
		center: centerOn,
		mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	map = new google.maps.Map(document.getElementById(divId), mapOptions);
	google.maps.event.trigger(map, 'resize');
	}

function createMap(){
	if (jsSlngd != 0){
		myPosition = new google.maps.LatLng(jsSlat, jsSlng);
		mydest = new google.maps.LatLng(jsSlatd, jsSlngd);
		initMap(myPosition,12,"mapholder");
		addPointMap(myPosition,"You","images/male.png","user");
		addPointMap(mydest,"Your destination","images/mydest.png",true);
		arrayMap(jsSnearby);
		}
	else {
		directionDisplay.setMap(null);
		myPosition = new google.maps.LatLng(jsSlat, jsSlng);
		initMap(myPosition,12,"mapholder");
		addPointMap(myPosition,"You","images/male.png","user");
		arrayMap(jsSnearby);
		}
	}

function arrayMap(locations){

	var marker, i;
	if(locations == null) return 1;

	// Current locations
	for (i = 0; i < locations.length; i++) { 
		if(locations[i][3]==="need") image1 ="images/walking.png";
 		else if(locations[i][3]==="offer") image1 ="images/car.png";
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]), 
			map: map, 
			icon: image1, 
			animation: google.maps.Animation.DROP
			});
		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				InfoWindow.setContent(locations[i][0]);
				InfoWindow.open(map, marker);
				}
			})(marker, i));
		markers.push(marker);
		}

	// Destination locations
	for (i = 0; i < locations.length; i++) {	
		if(locations[i][3]==="offer"){
			marker = new google.maps.Marker({position: new google.maps.LatLng(locations[i][4], locations[i][5]), map: map, icon: "images/dest.png", zIndex: 10000, animation: google.maps.Animation.DROP });
			var start;
			var end;

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					$('#driverMapInfo').show();
					if((locations[i][9] != null) && (locations[i][11] != null)){
						var time = " They are leaving at " + userTime(locations[i][9]) + " on " + toDays(locations[i][11]);
						if(locations[i][10] != null) var time = " They are leaving at " + userTime(locations[i][9]) + " and returning at " + userTime(locations[i][10]) + " on " + toDays(locations[i][11]);
						}
					else {
						var pretime = readableDate(locations[i][8]);
						if(pretime!==false) var time = " Leaving at " + readableDate(locations[i][8]);
						else var time = " They haven't set their leave time yet. ";
						}
					if(locations[i][3]==="offer"){
						InfoWindow.setContent(locations[i][0]);
						route(locations[i][0], false, "distanceDiv");
						if(locations[i][6]!==null){
							if(locations[i][7]<=0){
								$('#driverMapInfo').html(locations[i][0]+' has a full car.');
								}
							else {
								if(locations[i][7]==1) var spots = locations[i][7] + " seat avalable.";
								else var spots = locations[i][7] + " seats avalable.";
								$('#driverMapInfo').html(locations[i][0]+' has '+spots+time+' <span id="distanceDiv" value="" ></span> <button id="askride" value="'+locations[i][0]+'" onclick="askForRide();" >Ask for ride</button>');
								}
							}
						else {
							var spots = "not set avalable seats yet.";
							$('#driverMapInfo').html(locations[i][0]+' has '+spots+time+' <span id="distanceDiv" value="" ></span> <button id="askride" value="'+locations[i][0]+'" onclick="askForRide();" >Ask for ride</button>');
							}
						}
					else {
						InfoWindow.setContent(locations[i][0]);
						}
					InfoWindow.open(map, marker);
					}
				})(marker, i));
			markers.push(marker);
			}
		}
	}

function askForRide(){
	var myrideval = $('#askride').val();
	$('#returnSpan').html("Asking "+myrideval+" for ride.<br>").fadeIn();
	$.ajax({
		type: "POST",
		url: dir+"/askForRide.php",
		data: {
			myride: myrideval,
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data);
			$('#returnSpan').delay(9000).fadeOut();
			myRide(table);
			}
		});
	event.preventDefault();
	}
	
function addPointMap(pos,content,image,isuser){
	if(isuser) var ontop = 9999999999;
	else var ontop = 0;
	var marker = new google.maps.Marker({
		position: pos,
		map: map,
		icon: image,
		zIndex: ontop,
		animation: google.maps.Animation.DROP
		});
	//bounds.extend(pos);
	//map.fitBounds(bounds);
	google.maps.event.addListener(marker, 'click', (function(marker, i) {
		return function() {
			InfoWindow.setContent(content);
			InfoWindow.open(map, marker);
			}
		})(marker));
	markers.push(marker);
	}

function codeAddress(image) {
	var geocoder = new google.maps.Geocoder();
	if(document.getElementById('togeocode').value === null){
		$('#returnSpan').show();
		$('#returnSpan').html("Enter a destination to search. ");
		$('#returnSpan').delay(9000).fadeOut();
		}
	else var address = document.getElementById('togeocode').value;
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var dest = new google.maps.Marker({			 
				position: results[0].geometry.location,
				map: map,
		 		icon: image,
				animation: google.maps.Animation.DROP,
				zIndex: 999999999
				});
			google.maps.event.addListener(dest, 'click', (function(dest, i) { return function() {
				InfoWindow.setContent('<button id="setDestB" name="setDestB" onclick="setDestClick();" >Set as destination</button>');
				InfoWindow.open(map, dest);
				}
			})(dest));
			google.maps.event.addListener(dest, 'click', function(evt){
				$('#GPSlatd').val(evt.latLng.lat().toFixed(8));
				$('#GPSlngd').val(evt.latLng.lng().toFixed(8));
				});
			markers.push(dest);
			}
		else {
			console.log('There was a gecode error : ' + status);
	 		}
		});
	}
