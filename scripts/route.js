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
	displayIn.innerHTML = "";
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
