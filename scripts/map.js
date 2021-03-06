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
function getNearby(range, callback){

	if( s.lat != null ){
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
	if((jsSlat == 0)&&(jsSlngd == 0)) {
		console.log("Error in nearby(); jsSlat "+jsSlat+" jsSlngd "+jsSlngd);
		callback();
		}
	else {
		getFromTable("carpool_members", get, conditions, function(data){
			if(data === "none") nearby(range+0.05, callback);
			else {		
				 if (typeof callback=="function"){
					var temp = JSON.parse(data);
					var near = [];
					window.nearby = [];
					for ( var i = 0 ; i < temp.length ; i++ ) {
						near.push({
							user: temp[i][0].charAt(0).toUpperCase() + temp[i][0].slice(1),	
							lat: temp[i][1],
							lng: temp[i][2],
							type: temp[i][3],
							latd: temp[i][4],
							lngd: temp[i][5],
							total: temp[i][6],
							available: temp[i][7],
							leave: temp[i][8],
							leave1: temp[i][9],
							leave2: temp[i][10],
							days: temp[i][11]
							});
						}
					window.nearby = near;
					callback(near);
					}
				}
			});
		}
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

	window.SpiderMaps = new OverlappingMarkerSpiderfier(map,{markersWontMove: true, markersWontHide: true});

	SpiderMaps.addListener('click', function(marker) {
		InfoWindow.setContent(marker.user);
		InfoWindow.open(map, marker);
		$('#driverMap').hide();
		if ( typeof marker.userInfo !== "undefined" ) markerClick(marker.userInfo);
		});
	}

function createMap(){
	if (jsSlngd != 0){
		myPosition = new google.maps.LatLng(jsSlat, jsSlng);
		mydest = new google.maps.LatLng(jsSlatd, jsSlngd);
		initMap(myPosition,12,"mapholder");
		addPointMap(myPosition,"You","images/male.png","start");
		addPointMap(mydest,"Your destination","images/mydest.png","dest");
		arrayMap(jsSnearby);
		}
	else if (jsSlng != 0){
		directionDisplay.setMap(null);
		myPosition = new google.maps.LatLng(jsSlat, jsSlng);
		initMap(myPosition,12,"mapholder");
		addPointMap(myPosition,"You","images/male.png","user");
		arrayMap(jsSnearby);
		}
	else {
		directionDisplay.setMap(null);
		initMap(new google.maps.LatLng(45.50, -123.00),8,"mapholder");
		}
	}

function arrayMap(nearbyUsers){

	var marker, i;
	if(nearbyUsers == null) return 1;

	// Current locations
	for (i = 0; i < nearbyUsers.length; i++) { 
		if( nearbyUsers[i].type === "need" ) image1 = "images/walking.png";
 		else if( nearbyUsers[i].type === "offer" ) image1 = "images/car.png";
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(nearbyUsers[i].lat, nearbyUsers[i].lng), 
			map: map, 
			icon: image1, 
			animation: google.maps.Animation.DROP
			});
		marker.userInfo = nearbyUsers[i];
		marker.user = nearbyUsers[i].user;
		SpiderMaps.addMarker(marker);
		markers.push(marker);
		}

	// Destination locations
	for (i = 0; i < nearbyUsers.length; i++) {	
		if(nearbyUsers[i].type==="offer"){
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(nearbyUsers[i].latd, nearbyUsers[i].lngd), 
				map: map, 
				icon: "images/dest.png", 
				animation: google.maps.Animation.DROP 
				});
			marker.userInfo = nearbyUsers[i];
			marker.user = nearbyUsers[i].user;
			SpiderMaps.addMarker(marker);
			markers.push(marker);
			}
		}
	}

function markerClick(info){
	if(info.type === "offer"){
		$('#driverMap').show();
		if((info.leave1 != null) && (info.days != null)){
			var time = " " + info.user + " is leaving at " + userTime(info.leave1) + " on " + toDays(info.days);
			if(info.leave2 != null) var time = " " + info.user + " is leaving at " + userTime(info.leave1) + " and returning at " + userTime(info.leave2) + " on " + toDays(info.days);
			}
		else {
			var pretime = readableDate(info.leave);
			if(pretime !== false) var time = " Leaving at " + readableDate(info.leave);
			else var time = " " + info.user + " hasn't set their leave time yet. ";
			}
		route(info.user, false, "distanceDiv");
		if(info.total !== null){
			if(info.available <= 0){
				// Image
				//$('#driverMapPicCar').html('<img style="max-width:64px; max-height:64px;" align="left" src="images/cars/car'+ info[6] +'.png" >');
				// Text
				$('#driverMapInfo').html(info.user+' has a full car.');
				}
			else {
				if(info.available == 1) var spots = " 1 seat avalable.";
				else var spots = info.available + " seats avalable.";
				// Image
				//$('#driverMapPicCar').html('<img style="max-width:64px; max-height:64px;" align="left" src="images/cars/car'+ info[6] +'.png" >');
				// Text
				$('#driverMapInfo').html(info.user+' has '+spots+time+' <span id="distanceDiv" value="" ></span> <button id="askride" value="'+info.user+'" onclick="askForRide();" >Ask for ride</button>');
				}
			}
		else {
			var spots = "not set avalable seats yet.";
			// Image
			$('#driverMapPicCar').html('');
			// Text
			$('#driverMapInfo').html(info.user+' has '+spots+time+' <span id="distanceDiv" value="" ></span> <button id="askride" value="'+info.user+'" onclick="askForRide();" >Ask for ride</button>');
			}
		}
	}

function askForRide(){
	var myrideval = $('#askride').val();
	$('#returnSpan').html("Asking "+myrideval+" for ride.<br>").fadeIn();
	updateNull(table, "incar", " id = "+jsSid, function(){
		updateString(table, "ridingwith", myrideval, " id = "+jsSid, function(data){
			returnSpan(data);
			myRide(table);
			});
		});
	event.preventDefault();
	}
	
function addPointMap(pos,content,image,type){
	if(type) var ontop = 9999999999;
	else var ontop = 0;
	var marker = new google.maps.Marker({
		position: pos,
		map: map,
		icon: image,
		zIndex: ontop,
		draggable: true,
		animation: google.maps.Animation.DROP
		});
	marker.user = content;
	SpiderMaps.addMarker(marker);
	//bounds.extend(pos);
	//map.fitBounds(bounds);
	google.maps.event.addListener(marker, 'dragend', function(evt) {
		if(type === "dest"){
			$('#GPSlatd').val(evt.latLng.lat().toFixed(8));
			$('#GPSlngd').val(evt.latLng.lng().toFixed(8));
			setDestClick();
			}
		else if(type === "user" || type === "start"){
			$('#GPSlats').val(evt.latLng.lat().toFixed(8));
			$('#GPSlngs').val(evt.latLng.lng().toFixed(8));
			setLocationClick();
			}
		});
	markers.push(marker);
	}

function codeAddress(address, image, type) {
	var geocoder = new google.maps.Geocoder();
	if(address === ""){
		returnSpan("Enter a location to search. ");
		return 1;
		}
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			clearMarkers();
			map.setCenter(results[0].geometry.location);
			var dest = new google.maps.Marker({			 
				position: results[0].geometry.location,
				map: map,
		 		icon: image,
				animation: google.maps.Animation.DROP,
				zIndex: 999999999
				});
			SpiderMaps.addMarker(dest);
			google.maps.event.addListener(dest, 'click', (function(dest, i) { return function() {
				if(type === "dest"){
					dest.user = '<button onclick="setDestClick();" >Set as destination</button>';
					}
				if(type === "start"){
					dest.user = '<button onclick="setLocationClick();" >Set as starting location</button>';
					}
				InfoWindow.open(map, dest);
				}
			})(dest));
			google.maps.event.addListener(dest, 'click', function(evt){
				if(type === "dest"){
					$('#GPSlatd').val(evt.latLng.lat().toFixed(8));
					$('#GPSlngd').val(evt.latLng.lng().toFixed(8));
					}
				else if(type === "start"){
					$('#GPSlats').val(evt.latLng.lat().toFixed(8));
					$('#GPSlngs').val(evt.latLng.lng().toFixed(8));
					}
				});
			google.maps.event.addListener(dest, 'dragend', function(evt){
				if(type === "dest"){
					$('#GPSlatd').val(evt.latLng.lat().toFixed(8));
					$('#GPSlngd').val(evt.latLng.lng().toFixed(8));
					setDestClick();
					}
				else if(type === "start"){
					$('#GPSlats').val(evt.latLng.lat().toFixed(8));
					$('#GPSlngs').val(evt.latLng.lng().toFixed(8));
					setLocationClick();
					}
				});
			markers.push(dest);
			}
		else {
			console.log('There was a gecode error : ' + status);
			return 1;
	 		}
		});
	}
