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
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="<?php echo $dir; ?>/main.js"></script>
<script src="<?php echo $dir; ?>/map.js"></script>
<script src="<?php echo $dir; ?>/route.js"></script>
<script src="scripts/oms.min.js"></script>

<script>
$( document ).ready(function() {

	reload("<?php echo $_SESSION['id']; ?>");

	$('#type').change(function() {
		updateString(table,"type",$(this).val(),"id = "+jsSid, function(data){
			if(data === "Updated"){
				toggleMap();
				reload(jsSid);
				}
			else console.log(data);
			});
		});

	window.setInterval(function(){
		callEvery(table);
	
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
	updateString(table,"latestleave",ymd,"id = "+jsSid,function(data){
		updateNull(table,"rleave1","id = "+jsSid,function(data){
			updateNull(table,"rleave2","id = "+jsSid,function(data){
				updateNull(table,"days","id = "+jsSid,function(data){
					returnSpan("Your leave time was updated.<br>");
					getLeaveTime(table);
					});
				event.preventDefault();
				});
			event.preventDefault();
			});
		event.preventDefault();
		});
	event.preventDefault();
	}

function setLeave1() {
	// Change this table
	updateString(table,"rleave1",$('#time1').val()+':00',"id = "+jsSid,function(data){
		updateNull(table,"latestleave","id = "+jsSid,function(data){
			returnSpan("Your first leave time was updated.<br>");
			getLeaveTime(table);
			});
		});
	event.preventDefault();
	}

function setLeave2() {
	// Change this table
	updateString(table,"rleave2",$('#time2').val()+':00',"id = "+jsSid,function(data){
		updateNull(table,"latestleave","id = "+jsSid,function(data){
			returnSpan("Your second leave time was updated.<br>");
			getLeaveTime(table);
			});
		event.preventDefault();
		});
	}

function setDays() {
	// Change this table
	updateString(table,"days",getDays(),"id = "+jsSid,function(data){
		updateNull(table,"latestleave","id = "+jsSid,function(data){
			returnSpan("The days you drive on were updated.<br>");
			getLeaveTime(table);
			});
		event.preventDefault();
		});
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
		getFromTable(table,"latestleave","id = "+jsSid,function(data){
			data = JSON.parse(data);
			if(data[0][0] != null){
				var leave = timeArray(data[0][0]);
				$('#datetime').val(data[0][0]);
				$('#leavetime').html("You are currently set to leave at ");
				$('#time').val(leave[3] +":"+ leave[4] + ":" + leave[5]);
				$('#date').val(leave[2]);
				}
			else {
				$('#leavetime').html("You haven't set your leave time yet. ");
				$('#time').val("");
				$('#date').val("");
				}
			});
		}
	else if($('#leaveKind').val() === "repeat"){
		getFromTable(table,"rleave1, rleave2, days","id = "+jsSid,function(data){
			data = JSON.parse(data);
			data = data[0];
			if(data[0] != null){
				$('#time1').val(data[0]);
				$('#leavetime1').html("Your first leave time is ");
				$('#time').val("");
				$('#date').val("");
				}
			else {
				$('#leavetime1').html("You haven't set your first leave time yet. ");
				}
			if(data[1] != null){
				$('#time2').val(data[1]);
				$('#leavetime2').html("Your second leave time is ");
				$('#time').val("");
				$('#date').val("");
				}
			else {
				$('#leavetime2').html("You haven't set your second leave time yet. ");
				}
			if(data[2] != null){
				$('#yourDaysCurrent').html(data[2]);
				$('#yourDays').html("You are driving on"+toDays(data[2]));
				$('#time').val("");
				$('#date').val("");
				}
			else {
				$('#yourDays').html("You haven't set the days you're driving on yet. ");
				}
			});
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
	var updateTheseCords = ["latitude", "longitude"];
	var updatedCords = [];
	navigator.geolocation.getCurrentPosition(function(position){ 
  	    	updatedCords = [position.coords.latitude, position.coords.longitude];
		updateMultNum(table, updateTheseCords, updatedCords, "id = "+jsSid, function(data){
			if(data !== "Updated. ") {
				returnSpan("Couldn't update your location.<br>");
				}
			else {
				reload(jsSid);
				returnSpan("Updated your location.<br>");
				}
			});
		});
	return false;
	});

function setLocationClick(){
	var myLocation = [$('#GPSlats').val(), $('#GPSlngs').val()];
	var updateThese = ["latitude", "longitude"];
	updateMultNum(table, updateThese, myLocation, "id = "+jsSid, function(data){
		if(data !== "Updated. ") {
			returnSpan("Couldn't update your location.<br>");
			}
		else {
			deleteMarkers();
			reload(jsSid);
			returnSpan("Updated your location.<br>");
			}
		});
	event.preventDefault();
	}

function setDestClick(){
	var myDest = [$('#GPSlatd').val(), $('#GPSlngd').val()];
	var updateThese = ["dlatitude", "dlongitude"];
	updateMultNum(table, updateThese, myDest, "id = "+jsSid, function(data){
		if(data !== "Updated. ") {
			returnSpan("Couldn't update your destination.<br>");
			}
		else {
			deleteMarkers();
			reload(jsSid);
			returnSpan("Updated your destination.<br>");
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function clearRide($postto){ ?>
<span id="clearRideSpan" ></span>
<script>
function clearRide(){
	updateNull(table, "ridingwith", "id = "+jsSid, function(data){
		updateNull(table, "incar", "id = "+jsSid, function(data){
			reload(jsSid);
			});
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
	returnSpan("Clearing your destination. <br>");
	var values = ["NULL", "NULL"];
	var these = ["dlatitude", "dlongitude"];
	updateMultNum(table, these, values, " id = "+jsSid, function(data){
		if(data === "Updated. "){
			returnSpan("Cleared your destination.<br>");
			deleteMarkers();
			reload(jsSid);
			$('#driverMap').hide();
			}
		else returnSpan("Couldn't clear your destination.<br>");
		});
	event.preventDefault();
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
	// Update the Total Seats In users car
	returnSpan("Updating seats...<br>");
	var seatsval = $('#seats').val();
	updateNum(table, "spots", $('#seats').val(), "id = "+jsSid, function(data){
		if(data === "Updated"){
			returnSpan("Updated your total seats to "+$('#seats').val()+".<br>");
			//displaySeats();
			myCar();
			}
		else returnSpan("Couldn't update the total seats in your car.<br>");
		});
	event.preventDefault();
	}

function displaySeats(){
	// Display Seats Available
	getFromTable("carpool_members", "spots", " id = "+jsSid, function(data){
		data = JSON.parse(data)[0];
		$('#seats').val(data[0]);
		jsSspots = data[0];
		var tablenum = tableCheck(table);
		jsSavailablespots = jsSspots - jsSinmycar[tablenum].length;
		updateNum(table, "availablespots", jsSavailablespots, "id = "+jsSid, function(data){
			$('#availableSeats').html("There are "+jsSavailablespots+" available seats in your car.<br>");
			});
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
<br><hr /><br>
<script>
function updateMpg(){
	// Update the Mpg
	
	returnSpan("Updating mpg...<br>");
	updateNum("carpool_members", "mpg", $('#updateMpg').val(), "id = "+jsSid, function(data){
console.log(data);
		if(data === "Updated"){
			returnSpan(data+" your mpg.<br>");
			jsSmpg = $('#updateMpg').val();
			route(jsSusername, false, "myCarInfo");
			$('#myMpg').html("Your current mpg is "+jsSmpg+".<br>");
			$('#updateMpg').val(jsSmpg);
			}
		else returnSpan("Couldn't update your mpg.<br>");
		});
	event.preventDefault();
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
<span id="inMyCarSpan" ></span><br>
<span id="wantMyCarSpan" ></span><br><hr /><br>
<script>
function myCar(){
	if (jsSlngd != 0 ){
	var tablenum = tableCheck(table);
	getFromTable(table, "username, id", " incar ='"+jsSusername+"'", function(data){
		if(data !== "none") {
			data = JSON.parse(data);
			if( data.length !== jsSinmycar[tablenum].length ){
				jsSinmycar[tablenum] = [];
				for(var i = 0; i < data.length ; i++ ){ 
					jsSinmycar[tablenum].push( [] );
					jsSinmycar[tablenum][i].push(data[i][0]);
					jsSinmycar[tablenum][i].push(data[i][1]);
					}
				inMyCar(jsSinmycar[tablenum]);
				route(jsSusername, true, "myCarInfo");
				}
			}
		else inMyCar(null);
		displaySeats();
		});
	getFromTable(table, "username, id", " ridingwith ='"+jsSusername+"'", function(data){
		if(data !== "none") {
			data = JSON.parse(data);
				jsSwantmycar[tablenum] = [];
				for(var i = 0; i < data.length ; i++ ){ 
					jsSwantmycar[tablenum].push( [] );
					jsSwantmycar[tablenum][i].push(data[i][0]);
					jsSwantmycar[tablenum][i].push(data[i][1]);
					}
				wantMyCar(jsSwantmycar[tablenum]);
			}
		else wantMyCar(null);
		displaySeats();
		});
	}
	}

function inMyCar(incar){
	if(incar == null) {
		$('#inMyCarSpan').html("There is no one in your car.<br>");
		$('#myCarPic').html("");
		}
	else {
		if(incar.length == 1) $('#inMyCarSpan').html("There is one person in your car.<br>");
		else $('#inMyCarSpan').html("There are " + incar.length + " people in your car.<br>");
		for(var i = 0; i < incar.length; i++){
			$('#inMyCarSpan').append(incar[i][0]+'<button onclick="kickFromCar(this);" name="'+incar[i][0]+'" value="'+incar[i][1]+'" >kick</button><br>');
			}
		var carPic = "<img src='images/cars/car"+(incar.length+1)+".png' style='width:100%;' >";
		if($('#myCarPic').html() !== carPic) $('#myCarPic').html(carPic);
		}
	}

function wantMyCar(wantcar){
	if(wantcar == null) $('#wantMyCarSpan').html("There is no waiting to be approved for your car.<br>");
	else {
		$('#wantMyCarSpan').html("<form id='approvalForm' >");
		if(wantcar.length == 1){
			$('#wantMyCarSpan').append("There is one person waiting to be approved for your car.<br>");
			
			returnSpan("There is one person waiting to be approved for your car.<br>");
			
			}
		else {
			$('#wantMyCarSpan').append("There are " + wantcar.length + " people waiting to be approved for your car.<br>");
			
			returnSpan("There are " + wantcar.length + " people waiting to be approved for your car.<br>");
			
			}
		for(var i = 0; i < wantcar.length; i++){
			$('#wantMyCarSpan').append('Person number ' + (i+1) + ' is ' + wantcar[i][0]+'<input type="checkbox" id="accept" name="accept[]" value="' + wantcar[i][1] + '"><br>');
			}
		$('#wantMyCarSpan').append('<button id="acceptgo" onclick="approve()" >Accept</button></form><br>');
		}
	}

function approve(){
	var done = 0;
	var allDone = 0;
	$('#wantMyCarSpan :checkbox:checked').each(function(i){
		allDone = allDone + 2;
		updateNull(table, "ridingwith", "id = "+$(this).val(), function(){
			done++;
			if(allDone == done) shouldRoute();
			});
		updateString(table, "incar", jsSusername, "id = "+$(this).val(), function(){
			done++;
			if(allDone == done) shouldRoute();
			});
		});
	}

function kickFromCar(tokickel){
	$(tokickel).attr('value', function() {
		var id = $(this).val();
		var uname = $(this).attr("name");
		returnSpan("Kicking "+uname+"... <br>");
		updateNull(table, "incar", "id = "+id, function(){
			returnSpan("Kicked "+uname+".<br>");
			shouldRoute();
			});
		});
	}
</script>
<?php
	}

function myRide($postto){ ?>
<span id="myRideCar" >
<span id="myRideCarInfo" ></span><br>
<span id="myRideCarPic" ></span><br>
</span>
<span id="myRideSpan" ></span><br><hr /><br>
<script>
var initail = 0;
var othersInRide;

function myRide(table){
	// Change this for multi tables
	getFromTable(table, "ridingwith", "id = "+jsSid, function(data){
		data = JSON.parse(data);
		jsSridingwith = data[0][0];
		jsSride[tablenum].push(data[0][0]);
		//jsSride[tablenum].push(data[0][0]);
		if (jsSridingwith == null){
			getFromTable(table, "incar", "id = "+jsSid, function(data){
				data = JSON.parse(data);
				jsSincar = data[0][0];
				jsSride[tablenum].push(data[0][0]);
				if (jsSincar == null){
					$('#myRideSpan').html("You haven't asked anyone for a ride yet.<br>");
					$('#myRideCar').hide();
					if(jsStype !== "offer") directionDisplay.setMap(null);
					initail = 0;
					}
				else {
					$('#clearRideSpan').show();
					$('#clearRideSpan').html("<button id='clearRide' name='clearRide' onclick='clearRide()'>Remove me from "+ jsSincar +"'s car</button>");
					$('#myRideCar').show();
					getFromTable(table, "username", "incar = '"+jsSincar+"'", function(data){
						if (data !== "none"){
							if(initail == 0){
								initail = 1;
								othersInRide = data;
								inMyRide(JSON.parse(othersInRide),jsSincar);
								route(jsSincar, true, "myRideCarInfo");
								
								returnSpan("You've been approved to ride in "+jsSincar+"'s car. <br>");
								
								}
							else {
								if(data !== othersInRide){
									othersInRide = data;
									inMyRide(JSON.parse(othersInRide),jsSincar);
									route(jsSincar, true, "myRideCarInfo");
									}
								}
							$('#myRideCarPic').html("<img src='images/cars/car"+(JSON.parse(othersInRide).length+1)+".png' style='width:100%;' >");
							}
						else initail = 0;
						});
					}
				});
			}
		else {
			initail = 0;
			$('#myRideSpan').html("You haven't been approved to ride in "+jsSridingwith+"'s car yet.<br>");
			$('#myRideCar').hide();
			}
		});
	}

function tableCheck(table){
	if(table === "carpool_members") return 0;
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

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?

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
	<input id="getProfile" type="text" value="<?php echo $_SESSION['username']; ?>" ></input>
</div>
<h3 id="profileName" ></h3><br>
<img id="profilePicture" style="display: table; margin: 0 auto; max-width:128px; max-height:128px;" src='images/nopicture.png' align="left" >
<span id="profileInfo" ></span>
<span id="myProfile" ></span>
<br>

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
		returnSpan("Please select a file. <br>");	
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
			
			returnSpan(data+"<br>");
			
			profile(jsSusername.toLowerCase());
			},
		cache: false,
		contentType: false,
		processData: false
		});
	return false;
	});

profile($('#getProfile').val().toLowerCase());
var availableUsers = [];
getAllUsers(function(info){ availableUsers = info; });

$("#getProfile").keyup(function( event ) {
	profile($(this).val().toLowerCase());
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
				$('#profileInfo').html(readFile("profiles/infos/"+usernameval).replace(/\n/g, "<br>"));
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

			
			returnSpan(data[2]+"<br>");
			
			profile("<?php echo $_SESSION['username']; ?>");
			}
		});
	}

function showEditProfile(){
	$('#profileInfoEditText').val($('#profileInfo').html().replace(/<br>/g, '\n'));
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
