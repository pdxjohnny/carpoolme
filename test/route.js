// Make the map
var riderWaypoints = [];
var routeStart;
var routeEnd;

// Plot the route

function showRoute(user) {
	directionDisplay.setMap(map);
	getFromTable("username, latitude, longitude, dlatitude, dlongitude ", "username", user, 5, function(driverData){
		var driverInfo = JSON.parse(driverData);
		var routeStart = new google.maps.LatLng(driverInfo[0][1],driverInfo[0][2]);
		var routeEnd = new google.maps.LatLng(driverInfo[0][3],driverInfo[0][4]);
		getFromTable("username, latitude, longitude", "incar", user, 3, function(riderWaypointsNoParse){
			riderWaypoints = JSON.parse(riderWaypointsNoParse);
			if(riderWaypoints != null){
				var waypoints = [];
				for(var i = 0; i < riderWaypoints.length; i++){
					waypoints.push({location: new google.maps.LatLng(riderWaypoints[i][1], riderWaypoints[i][2]),stopover: true});
					}
				var request = {
					origin: routeStart,
					destination: routeEnd,
					waypoints: waypoints,
					travelMode: google.maps.TravelMode.DRIVING
					};
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionDisplay.setDirections(response);
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
						directionDisplay.setDirections(response);
						}
					});	
				}
			});
		});
	}
