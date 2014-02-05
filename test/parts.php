<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

define('INCLUDE_CHECK',true);

function includes($dir){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="<?php echo $dir; ?>/main.js"></script>

<script>
$( document ).ready(function() {
        $('#leavetime').html("You are currently set to leave at "+readableDate("<?php echo $_SESSION['latestleave']; ?>"));
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
			$('#returnSpan').html(data);
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

<button id="logout" onclick="logout()" value="Logout" >Logout</button>
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
			$('#returnSpan').html(data);
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}
</script>

<?php
	}

function setLatestLeave($postto){ ?>
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
<input value="Update Leave Time" id="setLatestLeave" name="setLatestLeave" type="submit">
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

	$( "#setLatestLeave" ).click(function( event ) {
		var datetimeval = $('#datetime').val();
		$('#leavetime').html("You are currently set to leave at "+readableDate(datetimeval));
		$.ajax(
			{
			type: "POST",
			url: "<?php echo $postto; ?>",
			data: {datetime: datetimeval, username: "<?php echo $_SESSION['username']; ?>"},
			success: function(data){
				$('#returnSpan').show();
				$('#returnSpan').html(data);
				$('#returnSpan').delay(9000).fadeOut();
				}
			});
		event.preventDefault();
		});
	});
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
			$('#returnSpan').html(data);
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
<button id="clearRide" name="clearRide" onclick="clearRide()">Clear Ride</button>
<script>
function clearRide(){
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
			$('#returnSpan').html(data);
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}



function clearDest($postto){ ?>
<button id="clearRide" name="clearRide" onclick="clearRide()">Clear Destination</button>
<script>
function clearRide(){
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
			$('#returnSpan').html(data);
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}
</script>
<?php
	}

function seats($postto){
	$whatname  = $_SESSION['username'];
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT username FROM $table WHERE ridingwith='$whatname' AND NOT username = '$whatname';");
	$_SESSION['numberWantSeats'] = mysqli_num_rows($result);

	$result = mysqli_query($con,"SELECT username FROM $table WHERE incar='$whatname' AND NOT username = '$whatname';");
	$_SESSION['numberApprovedSeats'] = mysqli_num_rows($result);
	
	$_SESSION['totalSeats'] = get("spots",$whatname);
	$_SESSION['numberavailableSeats'] = $_SESSION['totalSeats']-$_SESSION['numberApprovedSeats'];
	updateNum("availablespots",$_SESSION['numberavailableSeats'],$whatname);

	mysqli_close($con);?>
Update Seats Available: 
<select name="seats" id="seats">
<script>
for(var i = 1;i<=10;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<button onclick="seats()">Update</button><br>
</form>

<script>
function seats(){
	var seatsval = $('#seats').val();
	$.ajax({
		type: "POST",
		url: "<?php echo $postto; ?>",
		data: {
			seats: seatsval,  
			username: "<?php echo $_SESSION['username']; ?>"
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data);
			$('#returnSpan').delay(9000).fadeOut();
			}
		});
	event.preventDefault();
	}
</script>

<?php
	}
?>
