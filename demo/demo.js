
// Globals
var sitename = "carpool";
var dir = "scripts";
var table = "carpool_members"

// jsS - the javascript session variables
var jsSride = [ ["none", "none"], ["none", "none"], ["none", "none"], ["none", "none"], ["none", "none"] ];
var jsSESSION = [];
var jsSid;
var jsSusername;
var jsSpassword;
var jsSemail;
var jsSphone;
var jsSlatitude;
var jsSlongitude;
var jsStype;
var jsSlatd
var jsSlngd;
var jsSlatestleave;
var jsSspots;
var jsSridingwith;
var jsSincar;
var jsSavailablespots;
var jsSmpg;
var jsStripdistance;

// Reload jsS - the javascript session variables
function reload(myUsername){
	getMyUserInfo(myUsername, function(){
		console.log("reloaded");
		$('#type').val(jsStype);
		if(jsSincar != null) $('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from "+jsSincar+"'s car</button>");
		else if((jsSincar == null) && (jsSridingwith != null)) $('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from "+jsSridingwith+"'s car</button>");
		else $('#clearRideSpan').html("");
		if(jsSlngd != 0) $('#clearDest').show();
		else if(jsSlngd == 0) $('#clearDest').hide();
		if(jsStype==="offer"){
			getLeaveTime(table);
			myCar();
			$('#leaveSeatsMpg').show();
			$('#myCar').show();
			}
		else if(jsStype==="need"){
			$('#leaveSeatsMpg').hide();
			$('#myCar').hide();
			}
		myRide(table);
		if(jsSmpg != null) $('#myMpg').html("Your current mpg is "+jsSmpg+".<br>");
		else $('#myMpg').html("The mpg of your car is not set.<br>");
		createMap();
		if(jsSlngd != 0){
			directionDisplay.setMap(null);
			if(jsSincar != null){
				route(jsSincar, true, "myRideCarInfo");
				if(jsStype==="offer"){
					route(jsSusername, false, "myCarInfo");
					}
				}
			else if(jsStype==="offer"){
				route(jsSusername, true, "myCarInfo");
				}
			}
		});
	}

// Call these every x seconds
function callEvery(table){
	if(jsStype==="offer"){
		getLeaveTime(table);
		myCar();
		}
	myRide(table);
	}

// Main
function readFile(filename){
	filename = "/"+filename;
	var http=new XMLHttpRequest();
	http.open("GET",filename,false);
	http.send();
	return http.responseText;
	}

function tryParseJSON (jsonString){
	try {
		var o = JSON.parse(jsonString);
		if (o && typeof o === "object" ) {
			return o;
			}
		}
	catch (e) { }
	return false;
	}

function getMyUserInfo(user, callback){
	getUserInfo(user, function(userInfo){
		jsSESSION = userInfo[0];
		jsSusername = jsSESSION[0];
		jsSpassword = jsSESSION[1];
		jsSemail = jsSESSION[2];
		jsSlat = jsSESSION[3]*1;
		jsSlng = jsSESSION[4]*1;
		jsSlatd = jsSESSION[5]*1;
		jsSlngd = jsSESSION[6]*1;
		jsStype = jsSESSION[7];
		jsSmpg = jsSESSION[8];
		jsSincar = jsSESSION[9];
		jsSridingwith = jsSESSION[10];
		myPosition = new google.maps.LatLng(jsSlat, jsSlng);
		if(jsSlngd != 0) myDest = new google.maps.LatLng(jsSlatd, jsSlngd);
		nearby(0.05, function(nearMe){
			jsSnearby = nearMe;
			callback();
			});
		});
	}

function dateSufix(date){
	if(date == 1) {
		return "st";
		}
	else if(date == 2){
		return "nd";
		}
	else if(date == 3){
		return "rd";
		}
	else {
		return "th";
		}
	}

function timeArray(time){
	if(time!=null){
		var temp1 = time.split('-');
		var temp2 = temp1[2].split(' ');
		var temp3 = temp2[1].split(':');
		var out = [temp1[0], temp1[1], temp2[0], temp3[0], temp3[1], temp3[2]];
		return out;
		}
	}

function readableDate(mysqltime){
	var pre = timeArray(mysqltime);
	if(!pre) return false;
	day = pre[2];
	if(day[0] == 0) day = day[1];

	hour = pre[3];
	if(hour[0] == 0) hour = hour[1];

	minute = pre[4];
	if(day.length == 1) sufix = dateSufix(day);
	else if (day[0] == 1) sufix = dateSufix(day);
	else sufix = dateSufix(day[1]);
	
	if(hour < 12) {
		var ampm = "am";
		}
	else if(hour == 24) {
		var ampm = "am";
		}
	else {
		var ampm = "pm";
		}

	if(hour > 12) {
		hour = hour-12;
		}
	if(hour == 0) hour = 12;

	var toreturn = hour+':'+minute+' '+ampm+" on the "+day+sufix;
	return toreturn;
	}

function getUserInfo(user, callback){
	getFromTable("carpool_members", "username, password, email, latitude, longitude, dlatitude, dlongitude, type, mpg, incar, ridingwith ", "username ='"+user+"'", function(allUserInfo){
		callback(JSON.parse(allUserInfo));
		});
	}

function getFromTable(table, stuff, conditions, callback){
	//console.log("Table : "+table);
	//console.log("Getting : "+stuff);
	//console.log("Conditions : "+conditions);
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			get: stuff,
			conditions: conditions
			},
		success: function(data){
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
			}
		});
	}

function updateString(table, what, value, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			what: what,
			string: value,
			user: user
			},
		success: function(data){
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
			}
		});
	}

function updateNum(table, what, value, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			what: what,
			num: value,
			user: user
			},
		success: function(data){
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
			}
		});
	}	

function updateMultString(table, these, values, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			theseString: these,
			newvalues: values,
			id: id
			},
		success: function(data){
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
			}
		});
	}	

function updateMultNum(table, these, values, user, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			table: table,
			theseNum: these,
			newvalues: values,
			user: user
			},
		success: function(data){
			if (typeof callback=="function") callback(data);
			else console.log("The type of callback is not a function where data is : "+data);
			}
		});
	}
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
		var get = "username, latitude, longitude, type, dlatitude, dlongitude, spots, availablespots, latestleave";
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
					var pretime = readableDate(locations[i][8]);
					if(pretime!==false) var time = " Leaving at " + readableDate(locations[i][8]);
					else var time = " They haven't set their leave time yet. ";
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
// Route global vars
var renderOptions = { draggable: true, preserveViewport: true, };
var directionDisplay = new google.maps.DirectionsRenderer(renderOptions);
var directionsService = new google.maps.DirectionsService();

// Plot the route
function route(user, show, displayInDiv) {
	if (show == true) directionDisplay.setMap(map);
	getFromTable(table, "username, latitude, longitude, dlatitude, dlongitude, mpg ", "username = '"+user+"'", function(driverData){
		if(driverData === "none") console.log("In route, the driver data returned none where user is "+user);
		var driverInfo = JSON.parse(driverData);
		var routeStart = new google.maps.LatLng(driverInfo[0][1],driverInfo[0][2]);
		var routeEnd = new google.maps.LatLng(driverInfo[0][3],driverInfo[0][4]);
		getFromTable(table, "username, latitude, longitude", "incar = '"+user+"'", function(riderWaypointsNoParse){
			if(riderWaypointsNoParse != "none"){
				var riderWaypoints = [];
				riderWaypoints = JSON.parse(riderWaypointsNoParse);
				var waypoints = [];
				for(var i = 0; i < riderWaypoints.length; i++){
					waypoints.push({location: new google.maps.LatLng(riderWaypoints[i][1], riderWaypoints[i][2]),stopover: true});
					}
				var request = {
					origin: routeStart,
					destination: routeEnd,
					waypoints: waypoints,
					optimizeWaypoints: true,
					travelMode: google.maps.TravelMode.DRIVING
					};
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						if(show == true){
							directionDisplay.setDirections(response);
							}
						if(displayInDiv){
							var totalDistance = 0;
							for(var q = 0; q < response.routes[0].legs.length ; q++){
								totalDistance = response.routes[0].legs[q].distance.value + totalDistance;
								}
							displayDistance(driverInfo, totalDistance, displayInDiv);
							}
						}
					});
				}
			else {
				var request = {
					origin: routeStart,
					destination: routeEnd,
					travelMode: google.maps.TravelMode.DRIVING
					};
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						if(show == true){
							directionDisplay.setDirections(response);
							}
						if(displayInDiv){
							var totalDistance = 0;
							for(var q = 0; q < response.routes[0].legs.length ; q++){
								totalDistance = response.routes[0].legs[q].distance.value + totalDistance;
								}
							displayDistance(driverInfo, totalDistance, displayInDiv);
							}
						}
					});	
				}
			});
		});
	}

// Put the distance information in a div
function displayDistance(driverInfo, distance, displayInDiv){
	// var for the display element
	var displayIn = document.getElementById(displayInDiv);
	// Driver is taveling toMiles(totalDistance) miles. 
	var miles = toMiles(distance);
	if(jsSusername === driverInfo[0][0]){
		displayIn.innerHTML = "You are traveling " + miles + " miles. ";
		}
	else displayIn.innerHTML = driverInfo[0][0]+ " is traveling " + miles + " miles. ";
	// Get drivers mpg
	var mpg = driverInfo[0][5];
	// Handel no mpg set
	if (mpg == null) {
		displayIn.innerHTML += "Mpg has not been set to calculate the cost per person. ";
		}
	// If there is a mpg
	else {
		// Get totalRideCost, this is unsplit
		var totalRideCost = toDollars(miles,mpg);
		if(jsSusername === driverInfo[0][0]) displayIn.innerHTML += "Total cost of this trip is $"+ round2(totalRideCost) +". ";
		splitCost(totalRideCost,driverInfo[0][0], function(price){
			displayIn.innerHTML += "This will take $"+ round2(price) +" per person. <br>";
			});
		}
	}
	
// Converts meters to miles
function toMiles(meters){
	return round2(meters * 0.000621371192);
	}

// Converts meters to kilometers
function toKm(meters){
	return round2(meters / 1000);
	}

// Rounds a number to 2 decimal places
function round2(num){
	return Math.round(num*100)/100;
	}

// Finds the cost of a drivers distance
function toDollars(miles, mpg){
	// Need to add way to update gas price locally, possibly add localprice coloumn and average the values withing a range
	var priceofgas = 3.49;
	return (miles/mpg)*priceofgas;
	}

// Splits the cost based on how many users are in the car
function splitCost(totalDollars, user, callback){
	getFromTable(table, "username", "incar = '"+user+"'", function(jsondata){
		if(jsondata == "none"){
			if(jsSusername === user) callback(round2(totalDollars));
			else callback(totalDollars/2);
			}
		else if(jsSusername === user) callback(round2(totalDollars/(JSON.parse(jsondata).length+1)/*people + driver then you*/));
		// Add an if you are in the array so you are already in their car
		else callback(round2(totalDollars/(JSON.parse(jsondata).length+2)/*people + driver then you*/));
		});
	}
