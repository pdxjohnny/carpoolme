<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function getNearBy($range){
	
	$table = "carpool_members";
	$templat = $_SESSION['lat'];
	$templng = $_SESSION['lng'];
	$myusername = $_SESSION['username'];
	$mytype = $_SESSION['type'];

	$mylatsub = $templat-$range;
	$mylatadd = $templat+$range;
	$mylngsub = $templng-$range;
	$mylngadd = $templng+$range;

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT username, latitude, longitude, type FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND NOT type = '$mytype' AND NOT username = '$myusername';";

	if ($result = mysqli_query($con, $query)) {

		$starter=0;
	    	while ($row = mysqli_fetch_row($result)) {
			for($i = 0; $i < count($row); $i++){
				$_SESSION['nearby'][$starter][$i] = $row[$i];
				}
				$starter++;
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	if(!$_SESSION['nearby']) getNearBy($range+0.05);
	}

function showNearBy(){

	for($i = 0; $i < count($_SESSION['nearby']); $i ++){
		echo "Number ";
		echo $i+1 . " : ";
		for($j = 0; $j < count($_SESSION['nearby'][$i]); $j++){
			echo $_SESSION['nearby'][$i][$j] . " ";
			}
		echo "<br>";
		}
	}

function makeMap(){?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="scripts/main.js"></script>
  <script type='text/javascript' src='http://code.jquery.com/jquery-1.6.2.js'></script>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="destform">
<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlongd" id="GPSlongd" type="hidden" value="">
    <div id="panel">
      <input id="address" type="textbox" value="Destination">
      <input type="button" value="Geocode" onclick="codeAddress('images/male.png')">
    </div>
<div id="mapholder"></div>
</form>
<script>
	var mylat = "<?php echo $_SESSION['lat']; ?>";
   	var mylng = "<?php echo $_SESSION['lng']; ?>";
	var locations = <?php echo json_encode($_SESSION['nearby']); ?>;
	makeMap(mylat,mylng,12,"mapholder");
	addPointMap(mylat,mylng,"You","images/male.png",1);
	arrayMap(locations);
</script>
<?php
	}

function latestLeave(){?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="lateleaveform">
<select name="hour" id="hour">
<script>
for(var i = 1;i<=24;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
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
$( document ).ready(function() {
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
		console.log(ymd);
		});
	});
</script>
</select>
<input value="" id="datetime" name="datetime" type="hidden">
<input value="Latest Leave Time" id="setLatestLeave" name="setLatestLeave" type="submit">
</form>
<?php
	}
?>
