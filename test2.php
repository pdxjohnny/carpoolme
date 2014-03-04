<!DOCTYPE html>
<html>
	<head>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="test/main.js"></script>
		<title>jsUpdateMulti Test</title>
	</head>
<div id="returnSpan" ></div>
<br>
Select your trip 
<select id="tripSelect">
<option value="carpool_trip1" >Trip 1</option>
<option value="carpool_trip2" >Trip 2</option>
<option value="carpool_trip3" >Trip 3</option>
<option value="carpool_trip4" >Trip 4</option>
<option value="carpool_trip5" >Trip 5</option>
</select>

<div id="myname" >
Loading...
</div>
<br>
<div id="myRideSpan" >
</div>


<script>
var jsSride = [];
var initail = 0;



$('#tripSelect').change(function(){
	myRide($(this).val());
	getLeaveTime();
	});

function myRide(table){
	$('#myRideSpan').html("Loading...");
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
					}
				else {
					jsSride[ridenum][0] = data[0][0];
					jsSride[ridenum][1] = "good";
					getFromTable(table, "username", "incar", jsSride[ridenum][0], function(data){
						if (data !== "none"){
							var othersInRide = JSON.parse(data);
							inMyRide(othersInRide,jsSride[ridenum][0]);
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

function initRides(){
	for(var i = 0; i < 6; i++ ){
		jsSride[i] = [];
		}
	}
</script>
<br><br>

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
	getMyUserInfo("rysmith19", function(){
		$('#myname').html(jsSusername+" is logged in.");
		initRides();
		myRide("carpool_trip1");
		getLeaveTime();
		});
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

</html>
