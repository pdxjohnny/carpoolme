<!--
Application: Carpoolme.net
File: Home page
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function includes($dir){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php echo $dir; ?>/main.js"></script>

<script>
var jsmyride;

$( document ).ready(function() {

	getLeaveTime();
	$('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from my ride's car</button>");
	$('#leavetime').html("You are currently set to leave at "+readableDate("<?php echo $_SESSION['latestleave']; ?>"));

	myRide();
	<?php if(0==strcmp($_SESSION['type'],"offer")) echo "myCar();"; ?>

	window.setInterval(function(){
		// Functions that need to be called repeatedly evezy x seconds 
		<?php if(0==strcmp($_SESSION['type'],"offer")) echo "myCar();"; ?>
		myRide();
	
		}, 30000);

	});
</script>
<?php
	}

function test(){?>
<form id="form">
<input id="address" type="textbox" placeholder="Destination">
<input id="other" type="textbox" placeholder="other">
<input value="Submit" type="submit"><br>
</form>

<script>
$( document ).ready(function() {
	$( "#form" ).submit(function( event ) {
	var add = $('#address').val();
	var other = $('#other').val();
	$.ajax({
		type: "POST",
		url: "test3.php",
		data: {test1: add, test2: other},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	});
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
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}
</script>

<?php
	}

function setLatestLeave($postto){ ?>
<span id='leavetime'></span><br>
<select name="hour" id="hour">
<script>
for(var i = 0;i<24;i++){
	if(i==0) document.write("<option value='"+i+"'>"+12+"</option>");
	else if(i<=12) document.write("<option value='"+i+"'>"+i+"</option>");
	else document.write("<option value='"+i+"'>"+(i-12)+"</option>");
	}
</script>
</select>
:
<select name="minute" id="minute">
<script>
for(var i = 0;i<=60;i++){
	if(i<10) i = '0'+i;
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<span id="amorpm"></span>
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
	if(inputdate>maxdate) inputdate = inputdate-maxdate;
	document.write("<option value='"+inputdate+"'>"+inputdate+"</option>");
	}
</script>
</select>
<span id="datesufix"></span>
<input value="" id="datetime" name="datetime" type="hidden">
<button onclick="setLatestLeave()" id="setLatestLeave">Update Leave Time</button>
<script>
$( document ).ready(function() {
	$('#amorpm').html(" am");

	var val = $("#date").val();
	if(val.length == 1) var sufix = dateSufix(val);
	else if (val[0] == 1) var sufix = dateSufix(val);
	else var sufix = dateSufix(val[1]);
	$('#datesufix').html(sufix);

	$( '#setLatestLeave' ).click(function() {
		if((dateYMD.getMonth()+1)<10) var month = '0'+(dateYMD.getMonth()+1);
		else var month = dateYMD.getMonth()+1;
		var predate = $( "#date" ).val();
		var prehour = $( "#hour" ).val();
		var minute = $( "#minute" ).val();
		if(predate<10) var date = '0'+predate;
		else var date = predate;
		if(prehour<10) var hour = '0'+prehour;
		else var hour = prehour;
		var ymd = dateYMD.getFullYear()+'-'+month+'-'+date+' '+hour+':'+minute+':00';
		$('#datetime').val(ymd);
		});
	 $("#hour").click(function() {
		var val = $("#hour").val();
		if(val < 12) {
			$('#amorpm').html(" am");
			}
		else if(val == 24) {
			$('#amorpm').html(" am");
			}
		else {
			$('#amorpm').html(" pm");
			}
		});
	 $("#date").click(function() {
		var val = $(this).val();
		var sufix;
		if(val.length == 1) sufix = dateSufix(val);
		else if (val[0] == 1) sufix = dateSufix(val);
		else sufix = dateSufix(val[1]);
		$('#datesufix').html(sufix);
		});
	getLeaveTime();
	});

function setLatestLeave() {
	if((dateYMD.getMonth()+1)<10) var month = '0'+(dateYMD.getMonth()+1);
	else var month = dateYMD.getMonth()+1;
	var predate = $( "#date" ).val();
	var prehour = $( "#hour" ).val();
	var minute = $( "#minute" ).val();
	if(predate<10) var date = '0'+predate;
	else var date = predate;
	if(prehour<10) var hour = '0'+prehour;
	else var hour = prehour;
	var ymd = dateYMD.getFullYear()+'-'+month+'-'+date+' '+hour+':'+minute+':00';
	$('#datetime').val(ymd);
	var datetimeval = $('#datetime').val();
	$('#leavetime').html("You are currently set to leave at "+readableDate(datetimeval));
	$.ajax(
		{
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {datetime: datetimeval},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}

function getLeaveTime(){
	$.ajax(
		{
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {"getLeaveTime": "getLeaveTime"},
		success: function(data){
			$('#leavetime').html("You are currently set to leave at "+readableDate(data));
			}
		});
	}
</script>
<?php
	}

function setDest($postto){ ?>
<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlngd" id="GPSlngd" type="hidden" value="">

<span id="geocodeSpan">
<input id="togeocode" type="textbox" placeholder="Destination">
<input type="button" value="Find" onclick="codeAddress('images/mydest.png')">
</span>

<div id="mapholder"></div>

<script>
function setDestClick(){
	var GPSlatdval = $('#GPSlatd').val();
	var GPSlngdval = $('#GPSlngd').val();
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			GPSlatd: GPSlatdval, 
			GPSlngd: GPSlngdval, 
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	deleteMarkers();
	}
</script>
<?php
	}

function clearRide($postto){ ?>
<span id="clearRideSpan" ></span>
<script>
function clearRide(){
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			jsmyride = "nouserride";
			myRide();
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function clearDest($postto){ ?>
<button id="clearDest" name="clearDest" onclick="clearDest()">Clear Destination</button>
<script>
function clearDest(){
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function seats($update,$display){?>
Update Seats Available: 
<select name="seats" id="seats">
<script>
for(var i = 1;i<=10;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<button onclick="updateSeats();">Update</button><br><script>
function updateSeats(){
	// Update the Seats Available
	$('#returnSpan').show();
	$('#returnSpan').html("Updating seats...<br>");
	var seatsval = $('#seats').val();
	$.ajax({
		type: "POST",
		url: "<?php echo $update; ?>",
		data: {
			seats: seatsval,  
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			myCar();
			}
		});
	event.preventDefault();
	displaySeats();
	}

function displaySeats(){
// Display Updated Seats Available
	$.ajax({
		type: "POST",
		url: "<?php echo $display; ?>",
		data: { 
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#availableSeats').html(data);
			}
		});
	event.preventDefault();
	}
</script>
<span id="availableSeats"></span>
<?php
	}

function help($postto){ ?>
<button id="help" name="help" onclick="help()">Help</button>
<span id="helpSpan" style="display:none;"></span>
<script>
function help(){
	$('#helpSpan').toggle();
	$.ajax({
		type: "GET",
		url: "<?php echo $postto; ?>",
		data: {},
		success: function(data){
			$('#helpSpan').html(data);
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function myCar($postto){ ?>
<span id="inMyCarSpan" ></span>
<span id="wantMyCarSpan" ></span>
<script>
function myCar(){
	$.ajax({
		type: "GET",
		url: "<?php echo $postto; ?>",
		data: {},
		success: function(data){
			data = data.split('%');

			var incar = JSON.parse(data[0]);
			inMyCar(incar);

			var wantcar = JSON.parse(data[1]);
			wantMyCar(wantcar);
			displaySeats();
			}
		});
	}

function wantMyCar(wantcar){
	if(wantcar == null) $('#wantMyCarSpan').html("There is no waiting to be approved for your car.<br>");
	else {
		$('#wantMyCarSpan').html("<form id='approvalForm' >");
		if(wantcar.length == 1){
			$('#wantMyCarSpan').append("There is one person waiting to be approved for your car.<br>");
			$('#returnSpan').show();
			$('#returnSpan').html("There is one person waiting to be approved for your car.<br>");
			$('#returnSpan').delay(3000).fadeOut();
			}
		else {
			$('#wantMyCarSpan').append("There are " + wantcar.length + " people waiting to be approved for your car.<br>");
			$('#returnSpan').show();
			$('#returnSpan').html("There are " + wantcar.length + " people waiting to be approved for your car.<br>");
			$('#returnSpan').delay(3000).fadeOut();
			}
		for(var i = 0; i < wantcar.length; i++){
			$('#wantMyCarSpan').append('Person number ' + (i+1) + ' is ' + wantcar[i]+'<input type="checkbox" id="accept" name="accept[]" value="' + wantcar[i] + '"><br>');
			}
		$('#wantMyCarSpan').append('<button id="acceptgo" onclick="approve()" >Accept</button></form><br>');
		}
	}

function inMyCar(incar){
	if(incar == null) $('#inMyCarSpan').html("There is no one in your car.<br>");
	else {
		if(incar.length == 1) $('#inMyCarSpan').html("There is one person in your car.<br>");
		else $('#inMyCarSpan').html("There are " + incar.length + " people in your car.<br>");
		for(var i = 0; i < incar.length; i++){
			var tokick = '"'+incar[i][0]+'"';
			$('#inMyCarSpan').append(incar[i][0]+'<button onclick="kickFromCar(this);" value="'+incar[i][0]+'" >kick</button><br>');
			}
		}
	}

function approve(){
	var acceptval = [];
	$(':checkbox:checked').each(function(i){
		acceptval[i] = $(this).val();
		});
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",		
		data: {
			accept: acceptval
			},
		success: function(data){
			data = data.split('%');

			var returnval = data[2];
			$('#returnSpan').show();
			$('#returnSpan').html(returnval+"<br>");
			$('#returnSpan').delay(9000).fadeOut();

			var incar = JSON.parse(data[0]);
			var wantcar = JSON.parse(data[1]);
			myCar(wantcar);
			}
		});
	event.preventDefault();
	}

function kickFromCar(tokickel){
	$(tokickel).attr('value', function() {
		var tokickval = this.value;
		$('#returnSpan').show();
		$('#returnSpan').html("Kicking "+tokickval+"... <br>");
		$.ajax({
			type: "POST",
			url: "test/kick.php",		
			data: {
				tokick: tokickval
				},
			success: function(data){
				$('#returnSpan').show();
				$('#returnSpan').html(data+"<br>");
				$('#returnSpan').delay(9000).fadeOut();
				myCar();
				}
			});
		event.preventDefault();
		});
	}
</script>
<?php
	}

function myRide($postto){ ?>
<span id="myRideSpan" ></span>
<script>
function jsMyRide(){
	$.ajax({
		type: "GET",
		url: "<?php echo $postto; ?>",
		data: {},
		success: function(data){
			data = data.split('%');
			jsmyride = data[1];
			}
		});
	}

var initail = 0;
function myRide(){
	$.ajax({
		type: "GET",
		url: "<?php echo $postto; ?>",
		data: {},
		success: function(data){
			data = data.split('%');
			if(tryParseJSON(data[0])!=false){
				var incar = JSON.parse(data[0]);
				inMyRide(incar,data[1]);
				if(initail==0){
					initail = 1;
					$('#returnSpan').show();
					$('#returnSpan').html(data[2]+"<br>");
					$('#returnSpan').delay(9000).fadeOut();
					}
				}
			else {
				$('#myRideSpan').html(data[0]+"<br>");
				jsmyride = data[1];
				initail = 0;
				}
			}
		});
	}

function inMyRide(incar,ridename){
	if(incar == null) $('#myRideSpan').html("There is no one in "+ridename+"'s car.<br>");
	else {
		if(incar.length == 1) $('#myRideSpan').html("There is one person in "+ridename+"'s car.<br>");
		else $('#myRideSpan').html("There are " + incar.length + " people in "+ridename+"'s car.<br>");
		for(var i = 0; i < incar.length; i++){
			$('#myRideSpan').append('Person number ' + (i+1) + ' is ' + incar[i]+'<br>');
			}
		}
	}

</script>
<?php
	}

function login($postto){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
navigator.geolocation.getCurrentPosition(function(position){ 
      	$('#GPSlat').val(position.coords.latitude);
  	$('#GPSlng').val(position.coords.longitude);
	});
</script>

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
<input name="GPSlat" id="GPSlat" type="hidden" value="">
<input name="GPSlng" id="GPSlng" type="hidden" value="">
<center><input value="Login" id="login" name="login" type="submit"></center>
</form>
<script>

$('#loginfrom').submit(function(){
	$('#returnSpan').show();
	$('#returnSpan').html("Logging in...<br>");
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: $('#usernamel').val(), 
			password: $('#passwordl').val(),
			type: $('#typel').val(),
			GPSlat: $('#GPSlat').val(),
			GPSlng: $('#GPSlng').val()
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	});
</script>
<?php
	}

function register($postto){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
navigator.geolocation.getCurrentPosition(function(position){ 
      	$('#GPSlat').val(position.coords.latitude);
  	$('#GPSlng').val(position.coords.longitude);
	});
</script>

<form id="registerfrom">
<center><h4>Register</h4></center>
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
</select></center>
<input name="GPSlat" id="GPSlat" type="hidden" value="">
<input name="GPSlng" id="GPSlng" type="hidden" value="">
<center><input value="Register" id="reg" name="reg" type="submit"></center>
</form>
<script>

$('#registerfrom').submit(function(){
	$('#returnSpan').show();
	$('#returnSpan').html("Registering...<br>");
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: $('#usernamer').val(), 
			password: $('#passwordr').val(), 
			confirmpassword: $('#confirmpassword').val(), 
			email: $('#email').val(),
			type: $('#typer').val(),
			GPSlat: $('#GPSlat').val(),
			GPSlng: $('#GPSlng').val()
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	});
</script>
<?php
	}
?>
