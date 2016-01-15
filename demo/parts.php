<!--
Application: Carpoolme.net
File: parts
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function includes($dir){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="<?php echo $dir; ?>/main.js"></script>
<script src="<?php echo $dir; ?>/map.js"></script>
<script src="<?php echo $dir; ?>/route.js"></script>
<script src="<?php echo $dir; ?>/oms.min.js"></script>

<script>
$( document ).ready(function() {

	createMap();

	$('#type').change(function() {
		updateString(table,"type",$(this).val(),"id = "+s.id, function(data){
			if(data === "Updated"){
				toggleMap();
				reload(s.id);
				}
			else console.log(data);
			});
		});

	window.setInterval(function(){
		//callEvery(table);
	
		}, 30000);

	});
</script>
<?php
	}

function logout($postto){?>

<button class="remove-bottom" id="logout" onclick="logout()" value="Logout" >Logout</button>
<script>
function logout(){
	var logoutval = $('#logout').val();
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			logout: logoutval
			},
		success: function(data){
			returnSpan(data+"<br>");
			}
		});
	event.preventDefault();
	}
</script>

<?php
	}

function setLatestLeave($postto){ ?>
<select id="leaveKind" >
  <option value="once">One Time</option>
  <option value="repeat">Repeating</option>
</select>

<span id="leaveOnce" >
<div id='leavetime'></div>
<input id='time' type='time'>
on the
<select name="date" id="date">
<script>
var dateYMD = new Date();
var month = dateYMD.getMonth();
var maxdate = new Date(dateYMD.getFullYear(), month + 1, 0);
maxdate = maxdate.getDate();
var inputdate;
for(var i = 0;i<=14;i++){
	inputdate = dateYMD.getDate()+i;
	if(inputdate>maxdate){
		inputdate = inputdate-maxdate;
		}
	document.write("<option value='"+inputdate+"'>"+inputdate+"</option>");
	}
</script>
</select>
<span id="datesufix"></span>
<input value="" id="datetime" name="datetime" type="hidden">
<button onclick="setLatestLeave()" id="setLatestLeave">Update Leave Time</button>
</span>

<span id="leaveRepeat"  style="display:none;" >
<div id='leavetime1'></div>
<input id="time1" type="time" />
<button onclick="setLeave1()" id="setLeave1">Update First Leave Time</button>

<!-- Add Round Trip? toggle -->

<div id='leavetime2'></div>
<input id="time2" type="time" />
<button onclick="setLeave2()" id="setLeave2">Update Second Leave Time</button>
<br>
<div id="yourDays" > </div>
<a href="#" id="toggleDays" >Days</a>
<span id="rDays" style="display:none;" ><br>
<input type="checkbox" value="0">Sunday<br>
<input type="checkbox" value="1">Monday<br>
<input type="checkbox" value="2">Tuesday<br>
<input type="checkbox" value="3">Wednesday<br>
<input type="checkbox" value="4">Thursday<br>
<input type="checkbox" value="5">Friday<br>
<input type="checkbox" value="6">Saturday<br>
<button onclick="setDays()" id="setDays">Update Days</button>
</span>
</span>

<script>
$( document ).ready(function() {
	var val = $("#date").val();
	if(val.length == 1) var sufix = dateSufix(val);
	else if (val[0] == 1) var sufix = dateSufix(val);
	else var sufix = dateSufix(val[1]);
	$('#datesufix').html(sufix);
	});

$("#date").click(function() {
	var val = $(this).val();
	var sufix;
	if (val !== null){
		if(val.length == 1) sufix = dateSufix(val);
		else if (val[0] == 1) sufix = dateSufix(val);
		else sufix = dateSufix(val[1]);
		$('#datesufix').html(sufix);
		}
	});

$('#leaveKind').change(function(){
	getLeaveTime(table);
	if ($(this).val() === "repeat"){
		$('#leaveRepeat').show();
		$('#leaveOnce').hide();
		}
	if ($(this).val() === "once"){
		$('#leaveOnce').show();
		$('#leaveRepeat').hide();
		}
	});

$("#toggleDays").click(function() {
	$('#rDays').toggle();
	});

function setLatestLeave() {
	if((dateYMD.getMonth()+1)<10) var month = '0'+(dateYMD.getMonth()+1);
	else var month = dateYMD.getMonth()+1;
	var predate = $( "#date" ).val();
	if(predate<10) var date = '0'+predate;
	else var date = predate;
	var ymd = dateYMD.getFullYear()+'-'+month+'-'+date+' '+$('#time').val()+':00';
	// Change this table
	s.latestleave = ymd;
	s.leave1 =null;
	s.leave2 =null;
	s.days = null;
	returnSpan("Your leave time was updated.<br>");
	getLeaveTime(table);
	}

function setLeave1() {
	// Change this table
	s.leave1 = $('#time1').val()+':00';
	s.latestleave = null;
	returnSpan("Your first leave time was updated.<br>");
	getLeaveTime(table);
	}

function setLeave2() {
	// Change this table
	s.leave2 = $('#time2').val()+':00';
	s.latestleave = null;
	returnSpan("Your second leave time was updated.<br>");
	getLeaveTime(table);
	}

function setDays() {
	// Change this table
	s.days = getDays();
	returnSpan("The days you drive on were updated.<br>");
	getLeaveTime(table);
	}

function getDays(){
	var days = [];
	$('#rDays :checkbox:checked').each(function(i){
		days[i] = $(this).val();
		});
	return toNumDays(days);
	}

function getLeaveTime(table){
	if($('#leaveKind').val() === "once"){
		if(s.latestleave != null){
			var leave = timeArray(s.latestleave);
			$('#datetime').val(s.latestleave);
			$('#leavetime').html("You are currently set to leave at ");
			$('#time').val(leave[3] +":"+ leave[4] + ":" + leave[5]);
			$('#date').val(leave[2]);
			}
		else {
			$('#leavetime').html("You haven't set your leave time yet. ");
			$('#time').val("");
			$('#date').val("");
			}
		}
	else if($('#leaveKind').val() === "repeat"){
		if(s.leave1 != null){
			$('#time1').val(s.leave1);
			$('#leavetime1').html("Your first leave time is ");
			$('#time').val("");
			$('#date').val("");
			}
		else {
			$('#leavetime1').html("You haven't set your first leave time yet. ");
			}
		if(s.leave2 != null){
			$('#time2').val(s.leave2);
			$('#leavetime2').html("Your second leave time is ");
			$('#time').val("");
			$('#date').val("");
			}
		else {
			$('#leavetime2').html("You haven't set your second leave time yet. ");
			}
		if(s.days != null){
			$('#yourDaysCurrent').html(s.days);
			$('#yourDays').html("You are driving on"+ toDays(s.days) );
			$('#time').val("");
			$('#date').val("");
			}
		else {
			$('#yourDays').html("You haven't set the days you're driving on yet. ");
			}
		}
	}
</script>
<?php
	}

// Map
function setDest($postto){ ?>
<div id="driverMap" class="sixteen columns remove-bottom" >
<span id="driverMapPicCar" ></span>
<span id="driverMapInfo" ></span>
</div>

<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlngd" id="GPSlngd" type="hidden" value="">

<input name="GPSlats" id="GPSlats" type="hidden" value="">
<input name="GPSlngs" id="GPSlngs" type="hidden" value="">

<div style="display: table; margin: 0 auto;">
<form id="startGeocodeSpan" style="display: inline;" >
<input id="startToGeocode" type="textbox" placeholder="Starting Location">
<input type="submit" value="Find">
</form>
<a id="useMyLocation" href="#" >Use Current Location</a> 
<form id="destGeocodeSpan" style="display: inline;" >
<input id="destToGeocode" type="textbox" placeholder="Destination">
<input type="submit" value="Find">
</form>
</div>
<div id="mapholder" style="height:340px; width:100%;" ></div>

<script>
$( '#destGeocodeSpan' ).submit(function() {
	codeAddress($('#destToGeocode').val(), 'images/mydest.png', "dest");
	return false;
	});

$( '#startGeocodeSpan' ).submit(function() {
	codeAddress($('#startToGeocode').val(), 'images/male.png', "start");
	return false;
	});

$( '#useMyLocation' ).click(function() {
	navigator.geolocation.getCurrentPosition(function(position){ 
  	    	s.lat = position.coords.latitude;
		s.lng = position.coords.longitude;
		createMap();
		});
	});

function setLocationClick(){
	s.lat = Number($('#GPSlats').val());
	s.lng = Number($('#GPSlngs').val());
	createMap();
	}

function setDestClick(){
	s.latd = Number($('#GPSlatd').val());
	s.lngd = Number($('#GPSlngd').val());
	createMap();
	}
</script>
<?php
	}

function clearRide($postto){ ?>
<span id="clearRideSpan" ></span>
<script>
function clearRide(){
	returnSpan("Sign in to clear who you're riding with<br>");
	}
</script>
<?php
	}

function clearDest($postto){ ?>
<button id="clearDest" style="display: none;" onclick="clearDest()">Clear Destination</button>
<script>
function clearDest(){
	returnSpan("Clearing your destination.<br>");
	s.latd = null;
	s.lngd = null;
	returnSpan("Cleared your destination.<br>");
	deleteMarkers();
	createMap();
	$('#driverMap').hide();
	}
</script>
<?php
	}

function seats($update,$display){?>
<br><br>
<div id="totalSeats"></div>
<div id="availableSeats"></div>
Total seats in your car: 
<select name="seats" id="seats">
<script>
for(var i = 1;i<=10;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<button onclick="updateSeats();">Update</button><br><script>
function updateSeats(){
	s.spots = $('#seats').val();
	}

function displaySeats(){
	s.spots = $('#seats').val();
	s.availablespots = s.spots;
	$('#availableSeats').html("There are "+s.availablespots+" available seats in your car.<br>");
	}
</script>
<?php
	}

function mpg($postto){?>
<br>
<span id="myMpg"></span>
Update Your Mpg: 
<input id="updateMpg" type="number" style="width:30px"></input>
<button onclick="updateMpg();">Update</button>
<br>
<br><hr /><br>
<script>
function updateMpg(){
	returnSpan("Updated your mpg.<br>");
	s.mpg = $('#updateMpg').val();
	}
</script>
<?php
	}

function help($postto){ ?>
<button id="help" name="help" onclick="help()">Help</button>
<span id="helpSpan" style="display:none;"></span><hr /><br>
<script>
function help(){
	$('#helpSpan').toggle();
	$('#helpSpan').html(readFile("help.php"));
	}
</script>
<?php
	}

function myCar($postto){ ?>
<span id="myCarInfo" ></span><br>
<span id="myCarPic" ></span>
<span id="inMyCarSpan" >Sign Into see who's in your car.</span><br>
<span id="wantMyCarSpan" >People who have asked you for a ride will appear here.</span><br><hr /><br>
<?php
	}

function myRide($postto){ ?>
<span id="myRideCar" >
Sign in to see information on your ride's car.<br>
</span>
<?php
	}

function login($postto){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<form id="loginfrom">
<center><h4>Login</h4></center>
Username<br>
<input id='usernamel' type="text"><br>
Password<br>
<input id="passwordl" type="password"><br>
<center><select id="typel" name="type">
  <option value="need">Need Ride</option>
  <option value="offer">Offering Ride</option>
</select></center>
Remember me 
<input type ="checkbox" id="cookiel" >
<center><input value="Login" id="login" name="login" type="submit"></center>
</form>
<script>

$('#loginfrom').submit(function(){
	
	returnSpan("Logging in...<br>");
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: $('#usernamel').val(), 
			password: $('#passwordl').val(),
			type: $('#typel').val(),
			cookie: $('#cookiel').val()
			},
		success: function(data){
			
			returnSpan(data+"<br>");
			
			}
		});
	event.preventDefault();
	});
</script>
<?php
	}

function register($postto){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="scripts/main.js"></script>

<form id="registerfrom">
<center><h4>Register</h4></center>
<center>
Username<br>
<input id='usernamer' type="text"><br>
Password<br>
<input id="passwordr" type="password"><br>
Confirm password<br>
<input id="confirmpassword" type="password"><br>
Email<br>
<input id="email" type="text"><br>
<center><select id="typer" name="type">
  <option value="need">Need Ride</option>
  <option value="offer">Offering Ride</option>
</select></center><br>
Remember me 
<input type ="checkbox" id="cookier" >
</center>
<?php
require_once('scripts/recaptchalib.php');
$publickey = "6LcPK_ISAAAAAEYXUJAsrhUNTXMfmPpnzc2AOA3i";
echo recaptcha_get_html($publickey);
$resp = null;
$error = null;
?>
<center><input value="Register" id="reg" name="reg" type="submit"></center>
</form>
<script>

$('#registerfrom').submit(function(){
	returnSpan("Registering...<br>");
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: $('#usernamer').val(), 
			password: $('#passwordr').val(), 
			confirmpassword: $('#confirmpassword').val(), 
			email: $('#email').val(),
			type: $('#typer').val(),
			cookie: $('#cookier').val(),
			recaptcha_response_field: $('#recaptcha_response_field').val(),
			recaptcha_challenge_field: $('#recaptcha_challenge_field').val()
			},
		success: function(data){
			returnSpan(data+"<br>");
			}
		});
	return false;
	});
</script>
<?php
	}

function myProfile($postto){ ?>
<div class="ui-widget" style="display: table; margin: 0 auto;">
	<input id="getProfile" type="text" placeholder="username" ></input>
</div>
<h3 id="profileName" ></h3><br>
<img id="profilePicture" style="display: table; margin: 0 auto; max-width:128px; max-height:128px;" src='images/nopicture.png' align="left" >
<span id="profileInfo" ></span>
<span id="myProfile" ></span>
<br>
<script>
profile($('#getProfile').val().toLowerCase());

$("#getProfile").keyup(function( event ) {
	profile($(this).val().toLowerCase());
	});

function profile(usernameval){
	if (usernameval===""){
		$('#profilePicture').hide();
		$('#profileInfo').hide();
		$('#profileName').html("Type a user name to see their profile.<br>");
		return "no username given";
		}
	$('#profilePicture').show();
	$('#profileInfo').show();
	$('#profileName').html(usernameval);

	// Get picture and info
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: usernameval
			},
		success: function(data){
			data = data.split('%');
			if(data[0]!=="none") $('#profilePicture').attr("src", data[0]);
			else $('#profilePicture').attr("src", 'images/nopicture.png');
			if(data[1]==="exists")	$('#profileInfo').html(readFile("profiles/infos/"+usernameval).replace(/\n/g, "<br>"));
			else $('#profileInfo').html("Hasn't said anything about themself.<br>");
			}
		});
	}
</script>
<?php
	}

function myCarInfo($postto){ ?>
<span id="myCarInfo" >Loading distance and cost... </span><br>
<?php
	}
?>
