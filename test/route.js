// Make the map
var routeMap;
var routeBounds = new google.maps.LatLngBounds();
var markersArray = [];
var riderWaypoints = [];
var renderOptions = { draggable: true };
var directionDisplay = new google.maps.DirectionsRenderer(renderOptions);
var directionsService = new google.maps.DirectionsService();
		directionDisplay.setMap(routeMap);
var routeStart;
var routeEnd;

function makeRouteMap(user, divId) {
	getFromTable("username, latitude, longitude, dlatitude, dlongitude ", "username", user, 5, function(driverData){
		var driverInfo = JSON.parse(driverData);
		routeStart = new google.maps.LatLng(driverInfo[0][1],driverInfo[0][2]);
		routeEnd = new google.maps.LatLng(driverInfo[0][3],driverInfo[0][4]);
		var mapOptions = {
 			zoom: 12,
			center: routeStart,
			mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		map = new google.maps.Map(document.getElementById(divId), mapOptions);
		google.maps.event.trigger(routeMap, 'load');
		google.maps.event.trigger(routeMap, 'resize');
		directionDisplay.setMap(map);
		});
	}

function addMarker(pos,content,image,isuser) {
	var marker = new google.maps.Marker({
		position: pos,
		map: routeMap,
		icon: image,
		animation: google.maps.Animation.DROP
		});
	google.maps.event.addListener(marker, 'click', (function(marker, i) {
		return function() {
			InfoWindow.setContent(content);
			InfoWindow.open(map, marker);
			}
		})(marker));
	markersArray.push(marker);
	}

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
				//directionDisplay.setDirections(null);
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
				//directionDisplay.setDirections(null);
				directionsService.route(request, function(response, status) {
					if (status == google.maps.DirectionsStatus.OK) {
						directionDisplay.setDirections(response);
						}
					});	
				}
			});
		});
	}
