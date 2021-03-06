<!--
Application: Carpoolme.net
File: Home page
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function includes($dir){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="<?php echo $dir; ?>/main.js"></script>
<script src="<?php echo $dir; ?>/map.js"></script>
<script src="<?php echo $dir; ?>/route.js"></script>

<script>
$( document ).ready(function() {

	for(var i = 0; i < 6; i++ ){
		jsSride[i] = [];
		}
	reload("<?php echo $_SESSION['username']; ?>");

	$('#tripSelect').change(function(){
		myRide($(this).val());
		myCar($(this).val());
		getLeaveTime();
		});

	window.setInterval(function(){
		if(jsStype==="offer"){
			getLeaveTime();
			myCar($('#tripSelect').val());
			}
		myRide($('#tripSelect').val());
	
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
<script>
$( document ).ready(function() {
	$('#amorpm').html(" am");

	var val = $("#date").val();
	if(val.length == 1) var sufix = dateSufix(val);
	else if (val[0] == 1) var sufix = dateSufix(val);
	else var sufix = dateSufix(val[1]);
	$('#datesufix').html(sufix);
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
	updateString($('#tripSelect').val(),"leave1",ymd,jsSusername,function(data){
		$('#returnSpan').show();
		$('#returnSpan').html("Your leave time for trip "+tableCheck($('#tripSelect').val())+" was "+data+"<br>");
		$('#returnSpan').delay(9000).fadeOut();
		getLeaveTime();
		});
	event.preventDefault();
	}

function getLeaveTime(){
	getFromTable($('#tripSelect').val(),"leave1","username",jsSusername,function(data){
		data = JSON.parse(data);
		if(data[0][0] != null){
			$('#datetime').val(data[0][0]);
			$('#leavetime').html("You are currently set to leave at "+readableDate(data[0][0]));
			}
		else $('#leavetime').html("You haven't set your leave time yet. ");
		});
	}
</script>
<?php
	}

// Map
function setDest($postto){ ?>
<div id="driverMapInfo" style="display:none;" ></div>

<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlngd" id="GPSlngd" type="hidden" value="">


<form id="geocodeSpan"  style="display: table; margin: 0 auto;">
<input id="togeocode" type="textbox" placeholder="Destination">
<input type="submit" value="Find">
</form>
<div id="mapholder" style="height:340px; width:100%;" ></div>

<script>
$( '#geocodeSpan' ).submit(function() {
	codeAddress('images/mydest.png');
	return false;
	});

function setDestClick(){
	var GPSlatdval = $('#GPSlatd').val();
	var GPSlngdval = $('#GPSlngd').val();
	/*$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			GPSlatd: GPSlatdval, 
			GPSlngd: GPSlngdval, 
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			deleteMarkers();
			reload(jsSusername);
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		});*/
	event.preventDefault();
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
			reload(jsSusername);
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function clearDest($postto){ ?>
<button id="clearDest" style="display: none;" onclick="clearDest()">Clear Destination</button>
<script>
function clearDest(){
	$('#returnSpan').show();
	$('#returnSpan').html("Clearing your destination. <br>");
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
			deleteMarkers();
			reload(jsSusername);
			$('#driverMapInfo').hide();
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function seats($update,$display){?>
<br><br>
<span id="availableSeats"></span>
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
<?php
	}

function mpg($postto){?>
<br>
<span id="myMpg"></span>
Update Your Mpg: 
<input id="updateMpg" type="number" style="width:30px"></input>
<button onclick="updateMpg();">Update</button>
<br>
<br>
<script>
function updateMpg(){
	// Update the Mpg
	$('#returnSpan').show();
	$('#returnSpan').html("Updating mpg...<br>");
	$.ajax({
		type: "POST",
		url: dir+"/jsupdate.php",
		data: {
			what: "mpg",
			num: $('#updateMpg').val(),  
			user: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			jsSmpg = $('#updateMpg').val();
			route(jsSusername, false, "myCarInfo");
			$('#myMpg').html("Your current mpg is "+jsSmpg+".<br>");
			}
		});
	event.preventDefault();
	}
</script>
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
<span id="myCarInfo" ></span><br>
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

			if(data[0]!=="none") inMyCar(JSON.parse(data[0]));
			else inMyCar(null);

			if(data[1]!=="none") wantMyCar(JSON.parse(data[1]));
			else wantMyCar(null);

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
			var tokick = '"'+incar[i]+'"';
			$('#inMyCarSpan').append(incar[i]+'<button onclick="kickFromCar(this);" value="'+incar[i]+'" >kick</button><br>');
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

			route(jsSusername, true, "myCarInfo");
			myCar();
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
			url: "scripts/kick.php",		
			data: {
				tokick: tokickval
				},
			success: function(data){
				route(jsSusername, true, "myCarInfo");
				myCar();
				$('#returnSpan').show();
				$('#returnSpan').html(data+"<br>");
				$('#returnSpan').delay(9000).fadeOut();
				}
			});
		event.preventDefault();
		});
	}
</script>
<?php
	}

function myRide($postto){ ?>
<span id="myRideSpan" ></span><br>
<span id="myRideCarInfo" ></span><br>
<script>
var initail = [];

$('#tripSelect').change(function(){
	myRide($(this).val());
	});

function myRide(table){
	//$('#myRideSpan').html("Loading...");
	var ridenum = tableCheck(table);
	getFromTable(table, "ask", "username", jsSusername, function(data){
		if (data === "none"){
			$('#myRideSpan').html("Couldn't find a trip number "+ridenum+" for you. ");
			return 1;
			}
		data = JSON.parse(data);
		if (data[0][0] == null){
			getFromTable(table, "incar", "username", jsSusername, function(data){
				data = JSON.parse(data);
				if (data[0][0] == null){
					jsSride[ridenum][0] = data[0][0];
					jsSride[ridenum][1] = "none";
					$('#myRideSpan').html("You haven't asked anyone for a ride yet.<br>");
					$('#myRideCarInfo').html("");
					directionDisplay.setMap(null);
					initail[ridenum] = 0;
					}
				else {
					jsSride[ridenum][0] = data[0][0];
					jsSride[ridenum][1] = "good";
					getFromTable(table, "username", "incar", jsSride[ridenum][0], function(data){
						if (data !== "none"){
							var othersInRide = JSON.parse(data);
							inMyRide(othersInRide,jsSride[ridenum][0]);
							route(jsSincar, true, "myRideCarInfo");
							if(initail[ridenum] == 0){
								initail[ridenum] = 1;
								$('#returnSpan').show();
								$('#returnSpan').html("You've been approved to ride in "+jsSride[ridenum][0]+"'s car. <br>");
								$('#returnSpan').delay(9000).fadeOut();
								}
							}
						});
					}
				});
			}
		else {
			jsSride[ridenum][0] = data[0][0];
			jsSride[ridenum][1] = "wait";
			$('#myRideSpan').html("You haven't been approved to ride in "+jsSride[ridenum][0]+"'s car yet.<br>");
			}
		});
	}

function tableCheck(table){
	if(table === "carpool_temp") return 0;
	else if(table === "carpool_trip1") return 1;
	else if(table === "carpool_trip2") return 2;
	else if(table === "carpool_trip3") return 3;
	else if(table === "carpool_trip4") return 4;
	else if(table === "carpool_trip5") return 5;
	else return null;
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

function myProfile($postto){ ?>
<div class="ui-widget" style="display: table; margin: 0 auto;">
	<input id="getProfile" type="text" value="<?php echo $_SESSION['username']; ?>" ></input>
</div>
<h3 id="profileName" ></h3><br>
<img id="profilePicture" style="display: table; margin: 0 auto; max-width:128px; max-height:128px;" src='images/nopicture.png' align="left" >
<span id="profileInfo" ></span>
<span id="myProfile" ></span>

<span id="profileEditButtons" style="display:none;" >
	<button id="profileInfoEditButton" onclick='showEditProfile();' >Edit Profile</button>
	<form id="profilePictureUpload" enctype="multipart/form-data">
		<input name="file" type="file" />
		<input type="submit" name="submit" value="Upload">
	</form>
</span>

<span id="profileInfoEdit" style="display:none;" >
	<textarea id="profileInfoEditText" rows="20" cols="3000" ></textarea>
	<button onclick="updateProfile();">Update</button>
</span>
<script>

$('#profilePictureUpload').submit(function(){
	var formData = new FormData($('#profilePictureUpload')[0]);
	if(formData == null) {
		$('#returnSpan').show();
		$('#returnSpan').html("Please select a file. <br>");
		$('#returnSpan').delay(9000).fadeOut();	
		return false;	
		}
	$.ajax({
		url: 'profiles/pictures.php',
		type: 'POST',
		xhr: function() { 
			var myXhr = $.ajaxSettings.xhr();
			return myXhr;
			},
		data: formData,
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			profile(jsSusername);
			},
		cache: false,
		contentType: false,
		processData: false
		});
	return false;
	});

profile($('#getProfile').val());
var availableUsers = readFile("profiles/users").split('\n');

$("#getProfile").keyup(function( event ) {
	profile($(this).val());
	availableUsers = readFile("profiles/users").split('\n');
	});

function profile(usernameval){
	if (usernameval===""){
		$('#profilePicture').hide();
		$('#profileEditButtons').hide();
		$('#profileInfoEdit').hide();
		$('#profileInfo').hide();
		$('#profileName').html("Type a user name to see their profile.<br>");
		return "no username given";
		}
	$('#profilePicture').show();
	$('#profileInfo').show();
	$('#profileInfoEdit').hide();
	if(usernameval==="<?php echo $_SESSION['username']; ?>") $('#profileEditButtons').show();
	else $('#profileEditButtons').hide();
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
			
			if(data[1]==="exists"){
				$('#profileInfo').html(readFile("profiles/infos/"+usernameval).replace(/\n/g, "<br>")+"<br>");
				}
			else{
				if($('#getProfile').val()==="<?php echo $_SESSION['username']; ?>"){
					$('#profileInfo').html("Write about your self.<br>");

					}
				else $('#profileInfo').html("Hasn't said anything about themself.<br>");
				}
			}
		});
	}

function updateProfile(){
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			username: "<?php echo $_SESSION['username']; ?>",
			userinfo: $('#profileInfoEditText').val()
			},
		success: function(data){
			data = data.split('%');

			$('#returnSpan').show();
			$('#returnSpan').html(data[2]+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			profile("<?php echo $_SESSION['username']; ?>");
			}
		});
	}

function showEditProfile(){
	$('#profileInfoEditText').val($('#profileInfo').html().replace(/<br>/g, '\n')+" ");
	$('#profileInfoEdit').show();
	$('#profileInfo').hide();
	$('#profileEditButtons').hide();
	}

$( "#getProfile" ).autocomplete({
	source: function(request, response) {
		var results = $.ui.autocomplete.filter(availableUsers, request.term);
		response(results.slice(0, 5));
		}
	});

</script>
<?php
	}

function myCarInfo($postto){ ?>
<span id="myCarInfo" >Loading distance and cost... </span><br>
<?php
	}
?>
