<!DOCTYPE html>
<html>
	<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<title>Distance Matrix service</title>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
		<style>
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
	}

	#map-canvas {
		height: 100%;
		width: 50%;
	}
	#content-pane {
		float:right;
		width:48%;
		padding-left: 2%;
	}
		</style>
		<script>
// Make the map
var map;
var geocoder = new google.maps.Geocoder();
var bounds = new google.maps.LatLngBounds();
var markersArray = [];

var renderOptions = { draggable: true };
var directionDisplay = new google.maps.DirectionsRenderer(renderOptions);
var directionsService = new google.maps.DirectionsService();

var mylat = 55.487;
var mylng = 11.421;

var mylatd = 51.087;
var mylngd = 15.421;

var start = new google.maps.LatLng(mylat, mylng);
var end = new google.maps.LatLng(mylatd, mylngd);
var centerOn = new google.maps.LatLng( ((mylat+mylatd)/2), ((mylng+mylngd)/2) );

function initialize() {
	var mapOptions = {
		zoom: 3,
		center: centerOn
	}
	map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	addMarker(start, false);
	addMarker(end, true);
	directionDisplay.setMap(map);
	}

function addMarker(location, isDestination) {
	var icon;
	if (isDestination) {
		icon = "images/mydest.png";
		}
	else {
		icon = "images/male.png";
		}
	bounds.extend(location);
	map.fitBounds(bounds);
	var marker = new google.maps.Marker({
		map: map,
		position: location,
		icon: icon
		});
	markersArray.push(marker);
	}

google.maps.event.addDomListener(window, 'load', initialize);

// Plot the route and give the distances and total

var riders = [
	["rider1",54.087,12.421],
	["rider2",53.087,13.421],
	["rider3",52.087,14.421]
	];

function calcRoute() {
	var waypoints = [];
	for(var i = 0; i < riders.length; i++){
		waypoints.push({location: new google.maps.LatLng(riders[i][1], riders[i][2]),stopover: true});
		}
	var request = {
		origin: start,
		destination: end,
		waypoints: waypoints,
		travelMode: google.maps.TravelMode.DRIVING
		};
	directionsService.route(request, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionDisplay.setDirections(response);
			//distances(riderpos);
			}
		});
	}
/*
var allCallbacks = 0;
var totalCallbacks = false;
var totalDistance = 0;
if (riders){
	var riderpos = [];
	for(var i = 0; i < riders.length; i++){
		riderpos.push(new google.maps.LatLng(riders[i][1], riders[i][2]));
		}
	}

function distances(riderpos){
	totalDistance = 0;
	if (riderpos.length == 1){
		totalCallbacks = 2;

		calculateDistance(start, riderpos[0], function(distance){
			totalDistance = totalDistance + distance;
			calldone();
			});

		calculateDistance(riderpos[riderpos.length-1], end, function(distance){
			totalDistance = totalDistance + distance;
			calldone();
			});
		}
	else if (riderpos.length > 1){
		totalCallbacks = (2 + riderpos.length-1);

		calculateDistance(start, riderpos[0], function(distance){
			totalDistance = totalDistance + distance;
			calldone();
			});

		riderToRider(riderpos);

		calculateDistance(riderpos[riderpos.length-1], end, function(distance){
			totalDistance = totalDistance + distance;
			calldone();
			});
		}
	else {
		calculateDistance(start, end, function(distance){
			totalDistance = distance;
			callback(totalDistance);
			});
		}
	}

function calldone(){
	allCallbacks++;
	if(allCallbacks == totalCallbacks) {
		distanceDiv.innerHTML = totalDistance + " meters total. ";
		}
	}

var p = 0;
function riderToRider(riderpos) {
	calculateDistance(riderpos[p], riderpos[p+1], function(distance){
		totalDistance = totalDistance + distance;
		
		calldone();
		p++;

		if(p < riderpos.length-1){
			riderToRider(riderpos);
			}
		});
	}

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
			callback(meters);
			}
		});
	}
*/
		</script>
	</head>
	<body>

	<div id="content-pane">

		<div id="inputs">
			<pre id="vars">
			</pre>
			<button type="button" onclick="calcRoute();">Calculate distances</button>
		</div>

		<div id="distanceDiv"></div>
		<div id="outputDiv"></div>
	</div>

	<div id="map-canvas"></div>

	</body>
</html>
