// Route global vars
var renderOptions = { draggable: true, preserveViewport: true, };
var directionDisplay = new google.maps.DirectionsRenderer(renderOptions);
var directionsService = new google.maps.DirectionsService();

// Plot the route
function route(user, show, displayInDiv) {
	if (show == true) directionDisplay.setMap(map);
	getFromTable(table, "username, latitude, longitude, dlatitude, dlongitude, mpg, days", "username = '"+user+"'", function(driverData){
		if(driverData === "none") console.log("In route, the driver data returned none where user is "+user);
		var driverData = JSON.parse(driverData)[0];
		var driverInfo = {
			user: driverData[0],
			lat: driverData[1],
			lng: driverData[2],
			latd: driverData[3],
			lngd: driverData[4],
			mpg: driverData[5],
			roundTrip: driverData[6]
			};
		if (driverInfo.roundTrip !== null) driverInfo.roundTrip = true;
		else driverInfo.roundTrip = false; 
		var routeStart = new google.maps.LatLng(driverInfo.lat,driverInfo.lng);
		var routeEnd = new google.maps.LatLng(driverInfo.latd,driverInfo.lngd);
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
	displayIn.innerHTML = "";
	// Driver is taveling toMiles(totalDistance) miles. 
	var miles = toMiles(distance);
	if (driverInfo.roundTrip == true) miles = miles * 2;
	if(jsSusername === driverInfo.user){
		displayIn.innerHTML = "You are traveling " + miles + " miles. ";
		}
	else displayIn.innerHTML = driverInfo.user+ " is traveling " + miles + " miles. ";
	// Get drivers mpg
	var mpg = driverInfo.mpg;
	// Handel no mpg set
	if (mpg == null) {
		displayIn.innerHTML += "Mpg has not been set to calculate the cost per person. ";
		}
	// If there is a mpg
	else {
		// Get totalRideCost, this is unsplit
		var totalRideCost = toDollars(miles,mpg);
		if(jsSusername === driverInfo.user) displayIn.innerHTML += "Total cost of this trip is $"+ round2(totalRideCost) +". ";
		splitCost(totalRideCost,driverInfo.user, function(price){
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
	var priceofgas = 3.69;
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
		else {
			var imInTheCar = false;
			var splitOthersInCar = JSON.parse(jsondata);
			for (var i = 0; i < splitOthersInCar.length ; i++){
				if (splitOthersInCar[i][0] === jsSusername) var imInTheCar = true;
				}
			if (imInTheCar) callback(round2(totalDollars/(JSON.parse(jsondata).length+1)/*people + driver you're already in the car*/));
			else callback(round2(totalDollars/(JSON.parse(jsondata).length+2)/*people + driver then you*/));
			}
		});
	}

// Should we display the route?
function shouldRoute(){
	if(jsSlngd != 0){
		directionDisplay.setMap(null);
		if(jsSincar != null){
			route(jsSincar, true, "myRideCarInfo");
			}
		else if(jsStype==="offer"){
			route(jsSusername, true, "myCarInfo");
			}
		myRide();
		myCar();
		}
	}
