// Global Variables
var driverInfo = [];
var riderPositions = [];
var totalCallbacks = false;
var allCallbacks = 0;
var totalDistance = 0;
var p = 0;

// distanceInfo fills placeInDiv with the ride distance of user
function distanceInfo(user, placeInDiv){
	getFromTable("username, latitude, longitude, dlatitude, dlongitude, mpg ", "username", user, 6, function(driverData){
		driverInfo = JSON.parse(driverData);
		start = new google.maps.LatLng(driverInfo[0][1],driverInfo[0][2]);
		end = new google.maps.LatLng(driverInfo[0][3],driverInfo[0][4]);
		mpg = driverInfo[0][5];
		getFromTable("username, latitude, longitude", "incar", user, 3, function(usersInCar){
			if(JSON.parse(usersInCar) != null){
				makeriderPositions(JSON.parse(usersInCar));
				distances(start, end, riderPositions, placeInDiv);
				}
			else{
				distances(start, end, placeInDiv);
				}
			});
		});
	}

// Puts the riders into riderPositions 
function makeriderPositions(riders){
	// Empty riderPositions 
	riderPositions = [];
	// Loop through the riders and push their location into riderPositions 
	for(var i = 0; i < riders.length; i++){
		riderPositions.push(new google.maps.LatLng(riders[i][1], riders[i][2]));
		}
	}

// Sums the distances from all points
function distances(start, end, riderPositions, placeInDiv){
	// Clean these so that a new distance can be calculated
	allCallbacks = 0;
	totalCallbacks = false;
	totalDistance = 0;
	p = 0;
	// There are no riders in the car
	if (riderPositions == null){
		totalCallbacks = 1;
		calculateDistance(start, end, function(distance){
			totalDistance = distance;
			calldone(placeInDiv);
			});
		}
	// There is one rider in the car
	else if (riderPositions.length == 1){
		totalCallbacks = 2;

		calculateDistance(start, riderPositions[0], function(distance){
			totalDistance = totalDistance + distance;
			calldone(placeInDiv);
			});

		calculateDistance(riderPositions[riderPositions.length-1], end, function(distance){
			totalDistance = totalDistance + distance;
			calldone(placeInDiv);
			});
		}
	// There is more than one rider in the car
	else if (riderPositions.length > 1){
		totalCallbacks = (2 + riderPositions.length-1);

		calculateDistance(start, riderPositions[0], function(distance){
			totalDistance = totalDistance + distance;
			calldone(placeInDiv);
			});

		riderToRider(riderPositions);

		calculateDistance(riderPositions[riderPositions.length-1], end, function(distance){
			totalDistance = totalDistance + distance;
			calldone(placeInDiv);
			});
		}
	}

// Loop through the riderPositions array and add to totalDistance 
function riderToRider(riderPositions) {
	calculateDistance(riderPositions[p], riderPositions[p+1], function(distance){
		totalDistance = totalDistance + distance;
		
		// Call calldone(); to see if this is the last request
		calldone(placeInDiv);
		p++;

		if(p < riderPositions.length-1){
			riderToRider(riderPositions);
			}
		});
	}

// Get meters from point to point
function calculateDistance(point1, point2, callback) {

	var request = {
		origins: [point1],
		destinations: [point2],
		travelMode: google.maps.TravelMode.DRIVING,
		unitSystem: google.maps.UnitSystem.IMPERIAL,
		avoidHighways: false,
		avoidTolls: false
		};

	var service = new google.maps.DistanceMatrixService();
	service.getDistanceMatrix(request, function(response, status) {
		if (status != google.maps.DistanceMatrixStatus.OK) {
			console.log('getDistanceMatrix error was: ' + status);
			}
		else {
			var origins = response.originAddresses;
			var destinations = response.destinationAddresses;
			var results = response.rows[0].elements;
			var meters = results[0].distance.value;
			// Callback the result because of async
			callback(meters);
			}
		});
	}

// Checks if all callbacks are completed
function calldone(placeInDiv){
	// Incerment on call
	allCallbacks++;
	// Do this when all the callbacks have completed in distances
	if(allCallbacks == totalCallbacks) {
		// Driver is taveling toMiles(totalDistance) miles. 
    		document.getElementById(placeInDiv).innerHTML = driverInfo[0][0]+ " is traveling " + toMiles(totalDistance) + " miles. ";
		// Get drivers mpg
		var mpg = driverInfo[0][5];
		// Handel no mpg set
		if (mpg == null) {
			document.getElementById(placeInDiv).innerHTML += "They have not given their mpg to calculate the cost per person. ";
			}
		// If there is a mpg
		else {
			// Get totalRideCost, this is unsplit
			var totalRideCost = toDollars(toMiles(totalDistance),mpg);
			splitCost(totalRideCost,driverInfo[0][0], function(price){
				document.getElementById(placeInDiv).innerHTML += "This will take "+ price +" dollars. ";
				});
			}
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
	getFromTable("username", "incar", user, 1, function(jsondata){
		if(JSON.parse(jsondata) == null){
			console.log("dividing by 2");
			callback(totalDollars/2);
			}
		// Add an if you are in the array so you are already in their car
		else {
			console.log("dividing by " + (JSON.parse(jsondata).length+2));
			callback(round2(totalDollars/(JSON.parse(jsondata).length+2)/*people + driver then you*/));
			}
		});
	}
