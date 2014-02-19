var sitename = "carpool";
var dir = "test";

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
			}
		});
	event.preventDefault();
	myRide();
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

var map;
var InfoWindow = new google.maps.InfoWindow();

var markers = [];
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

function makeMap(centerOn,zoomval,divId){
	var mapOptions = {
 		zoom: zoomval,
		center: centerOn,
		mapTypeId: google.maps.MapTypeId.ROADMAP
		};
	map = new google.maps.Map(document.getElementById(divId), mapOptions);
	mapholder=document.getElementById(divId)
	mapholder.style.height='340px';
	mapholder.style.width='100%';
	}

function arrayMap(locations){

	var marker, i;

	// Current locations
	for (i = 0; i < locations.length; i++) { 
		if(locations[i][3]==="need") image1 ="images/walking.png";
 		else if(locations[i][3]==="offer") image1 ="images/car.png";
		marker = new google.maps.Marker({position: new google.maps.LatLng(locations[i][1], locations[i][2]), map: map, icon: image1 });
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
			marker = new google.maps.Marker({position: new google.maps.LatLng(locations[i][4], locations[i][5]), map: map, icon: "images/dest.png", zIndex: 10000 });
			var start;
			var end;

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					getFromTable("username, latitude, longitude, dlatitude, dlongitude ", "username", locations[i][0], 5, function(jsondata){
						var userinfo = JSON.parse(jsondata);
						start = new google.maps.LatLng(userinfo[0][1],userinfo[0][2]);
						end = new google.maps.LatLng(userinfo[0][3],userinfo[0][4]);
						
						getFromTable("username, latitude, longitude", "incar", "pdxjohnny"/*locations[i][0]*/, 3, function(jsondata){
							makeRiderPos(JSON.parse(jsondata));
							console.log(start);
							console.log(end);
							console.log(riderpos);
							distances(start, end, riderpos);
							});
						});
					var pretime = readableDate(locations[i][8]);
					if(pretime!==false) var time = " Leaving at " + readableDate(locations[i][8]);
					else var time = " No leave time set. ";
					if(locations[i][3]==="offer"){
						if(locations[i][6]!==null){
							if(locations[i][7]<=0){
								InfoWindow.setContent(locations[i][0]+' has a full car.');
								}
							else {
								if(locations[i][7]==1) var spots = locations[i][7] + " seat avalable.";
								else var spots = locations[i][7] + " seats avalable.";
								InfoWindow.setContent(locations[i][0]+' has '+spots+time+' They are travleing <span id="#distanceDiv" ></span><button id="askride" value="'+locations[i][0]+'" onclick="askForRide();" >Ask for ride</button>');
								}
							}
						else {
							var spots = "not set avalable seats yet.";
							InfoWindow.setContent(locations[i][0]+' has '+spots+time+'<button id="askride" value="'+locations[i][0]+'" onclick="askForRide();" >Ask for ride</button>');
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
	
function addPointMap(pos,content,image,isuser){
	if(isuser) var ontop = 9999999999;
	else var ontop = 0;
	var marker = new google.maps.Marker({
		position: pos,
		map: map,
		icon: image,
		zIndex: ontop
		});
	google.maps.event.addListener(marker, 'click', (function(marker, i) {
		return function() {
			InfoWindow.setContent(content);
			InfoWindow.open(map, marker);
			}
		})(marker));
	markers.push(marker);
	}

var dest;

function codeAddress(image) {
	var geocoder = new google.maps.Geocoder();
	if(document.getElementById('togeocode').value === null){
		$('#returnSpan').show();
		$('#returnSpan').html("Enter a destination to search. ");
		$('#returnSpan').delay(9000).fadeOut();
		}
	geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var dest = new google.maps.Marker({			 
				position: results[0].geometry.location,
				map: map,
		 		icon: image,
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

function getFromTable(stuff, something, isthis, howmany, callback){
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			get: stuff,
			something: something,
			isthis: isthis, 
			howmany: howmany
			},
		success: function(data){
			console.log(data);
			callback(data);
			}
		});
	event.preventDefault();
	}

// Distance Stuff

var allCallbacks = 0;
var totalCallbacks = false;
var totalDistance = 0;
var riderpos = [];
var p = 0;

function makeRiderPos(riders){
	riderpos = [];
	for(var i = 0; i < riders.length; i++){
		riderpos.push(new google.maps.LatLng(riders[i][1], riders[i][2]));
		}
	}

function distances(start, end, riderpos){
	totalDistance = 0;
	p = 0;
	if (riderpos.length == 1){
		totalCallbacks = 2;

		calculateDistance(start, riderpos[0], function(distance){
			totalDistance = totalDistance + distance;
			calldone("#distanceDiv"," meters. ");
			});

		calculateDistance(riderpos[riderpos.length-1], end, function(distance){
			totalDistance = totalDistance + distance;
			calldone("#distanceDiv"," meters. ");
			});
		}
	else if (riderpos.length > 1){
		totalCallbacks = (2 + riderpos.length-1);

		calculateDistance(start, riderpos[0], function(distance){
			totalDistance = totalDistance + distance;
			calldone("#distanceDiv"," meters. ");
			});

		riderToRider(riderpos);

		calculateDistance(riderpos[riderpos.length-1], end, function(distance){
			totalDistance = totalDistance + distance;
			calldone("#distanceDiv"," meters. ");
			});
		}
	else {
		calculateDistance(start, end, function(distance){
			totalDistance = distance;
			callback(totalDistance);
			});
		}
	}

function calldone(towhere,addwhat){
	allCallbacks++;
	if(allCallbacks == totalCallbacks) {
		console.log(totalDistance);
		
		//var putin = document.getElementById(towhere);
		$(towhere).html(totalDistance + addwhat);
		}
	}

function riderToRider(riderpos) {
	calculateDistance(riderpos[p], riderpos[p+1], function(distance){
		totalDistance = totalDistance + distance;
		
		calldone("#distanceDiv"," meters. ");
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


