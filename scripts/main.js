var sitename = "carpool";
var dir = "test";

var site = document.URL;
site = site.substring(site.indexOf("/") + 2);
site = site.substring(0, site.indexOf('/'));

function links(file,dir,linktype){

	var strings = readFile(file).split('\n');
	var links = stringsToLinks(strings,dir,linktype);
	for(var i=0;i<links.length;i++){
		document.write(links[i]);
		}
	}

function upload(subdir){
	document.write("<form action='scripts/upload.php' method='post' enctype='multipart/form-data'><input type='hidden' name='uploadto' value="+subdir+"/><input type='submit' name='submit' value='Upload'> <input type='file' name='file' id='file'></form>");
	}

function removeLeading(string,find){

	var temp = [];
	temp[0]='\0';
	var i=0;
	if(typeof find !== 'undefined'){
		for(var i=0;i<string.length;i++){
			if(string[i]==find){
				break;
				}
			}
		}
	for(var j=0;i<string.length;i++){
		temp[j]=string[i];
		temp[j+1]='\0';
		j++;
		}
	return temp;
	}

function stringsToLinks(list,directory,linktype){
	
	for (var i = 0 ; i < list.length ; i++){
		var n = list[i].indexOf('.');
		var temp = list[i].substring(0, n != -1 ? n : list[i].length);
		temp = temp.replace('_',' ');

		if(typeof linktype !== 'undefined'){
			if(linktype === "images"){
				if(typeof directory === 'undefined'){
					console.log("No directory passed to 'function stringsToImageLinks'");
					}
				else{
					if(i==0) var links = ["<li id="+temp+" class='figure'><a href="+directory+list[i]+"><img src="+directory+list[i]+" alt="+temp+" /><span class='figcaption'><b>"+temp+"</b></span></a></li>"];
					else links.push("<li id="+temp+" class='figure'><a href="+directory+list[i]+"><img src="+directory+list[i]+" alt="+temp+" /><span class='figcaption'><b>"+temp+"</b></span></a></li>");
					}
				}
			else if(linktype === "list"){
				if(typeof directory === 'undefined'){
					if(i==0) var links = ["<li class='first'><a href=/>Home</a></li>"];
					if (temp!=="index") links.push("<li class='first'><a href="+list[i]+">"+temp+"</a></li>");
					}
				else{
					if(i==0) var links = ["<li class='first'><a href="+directory+list[i]+">"+temp+"</a></li>"];
					else links.push("<li class='first'><a href="+directory+list[i]+">"+temp+"</a></li>");
					}
				}
			}
		else{
			if(typeof directory === 'undefined'){
				if(i==0) var links = ["<a href=/>Home</a>"];
				if ((temp!=="index")&&(temp!=="login")) links.push("<a href="+list[i]+">"+temp+"</a>");
				}
			else{
				if(i==0) var links = ["<a href="+directory+list[i]+">"+temp+"</a><br>"];
				else links.push("<a href="+directory+list[i]+">"+temp+"</a><br>");
				}
			}
		}
	return links;
	}		

function readFile(filename){
	var oRequest = new XMLHttpRequest();
	var sURL = /*"http://"
        	 + site
        	 +*/ "/readfiles/"
		 + filename;

	oRequest.open("GET",sURL,false);
	oRequest.setRequestHeader("User-Agent",navigator.userAgent);
	oRequest.send(null);

	if (oRequest.status==200){
		var res = oRequest.responseText;
		return res;
		}
	else alert("Error executing XMLHttpRequest call!");
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
	$.ajax(
		{
		type: "POST",
		url: "scripts/askForRide.php",
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

function userTime(time){

	if(time!=null){
		var temp1 = time.split('-');
		var temp2 = temp1[2].split(' ');
		var temp3 = temp2[1].split(':');
		var out = [temp1[0], temp1[1], temp2[0], temp3[0], temp3[1], temp3[2]];
		return out;
		}
	}

function readableDate(mysqltime){
	var pre = userTime(mysqltime);
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

function oneOnMap(lat,lng,lat1,lng1){

  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition);
    }
  else{x.innerHTML="Geolocation is not supported by this browser.";}


function showPosition(position){
  latlon=new google.maps.LatLng(mylat, mylng)
  latlon1=new google.maps.LatLng(lat1, lng1)
  mapholder=document.getElementById('mapholder')
  mapholder.style.height='250px';
  mapholder.style.width='100%';

  var myOptions={
  center:latlon,zoom:12,
  mapTypeId:google.maps.MapTypeId.ROADMAP,
  mapTypeControl:false,
  navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
  };
  var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);
  var mymarker=new google.maps.Marker({position:latlon,map:map,title:"You are here!"});
  }
}

var map;
var InfoWindow = new google.maps.InfoWindow();
//var setDestButton = "<?php require 'test2.php'; ?>";
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

function setDestButton(){
	document.write("<?php require 'test2.php'; ?>");
	}

function makeMap(centerlat,centerlng,zoomval,divId){

	map = new google.maps.Map(document.getElementById(divId), {
 		zoom: zoomval,
		center: new google.maps.LatLng(centerlat,centerlng),
		mapTypeId: google.maps.MapTypeId.ROADMAP
		});
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

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
        			return function() {
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
								InfoWindow.setContent(locations[i][0]+' has '+spots+time+'<button id="askride" value="'+locations[i][0]+'" onclick="askForRide();" >Ask for ride</button>');
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

function addPointMap(lat,lng,name,image,isuser){
	if(isuser) var ontop = 9999999999;
	else var ontop = 0;
	var marker = new google.maps.Marker({position: new google.maps.LatLng(lat,lng), map: map, icon: image, zIndex: ontop});

	google.maps.event.addListener(marker, 'click', (function(marker, i) {
        	return function() {
			InfoWindow.setContent(name);
			InfoWindow.open(map, marker);
			}
		})(marker));
	markers.push(marker);
	}

var dest;

function moveMarker( map , marker, Lat, Lng ) {

    marker.setPosition( new google.maps.LatLng( Lat, Lng) );
    map.panTo( new google.maps.LatLng( Lat, Lng) );

};

function placeMarker(location) {
    if (dest) {
        dest = google.maps.Marker({          
            position: location,
            map: map,
	    zIndex: 999999999
        });
    } else {
        //create a marker
        dest = new google.maps.Marker({          
            position: location,
            map: map,
	    zIndex: 999999999
        });
    }
	}

function codeAddress(image) {
  var geocoder = new google.maps.Geocoder();
  if(document.getElementById('togeocode').value === null) alert('No Destinatoin searched for.');
  var address = document.getElementById('togeocode').value;
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
	var dest = new google.maps.Marker({          
            position: results[0].geometry.location,
            map: map,
	    icon: image,
	    zIndex: 999999999
        });
	google.maps.event.addListener(dest, 'click', (function(dest, i) {
        	return function() {
			InfoWindow.setContent('<button id="setDestB" name="setDestB" onclick="setDestClick();" >Set as destination</button>');
			InfoWindow.open(map, dest);
			}
		})(dest));
	google.maps.event.addListener(dest, 'click', function(evt){
		$('#GPSlatd').val(evt.latLng.lat().toFixed(8));
  		$('#GPSlngd').val(evt.latLng.lng().toFixed(8));
		});
    } else {
      alert('There was an error : ' + status);
    }
  });
	markers.push(dest);
}
