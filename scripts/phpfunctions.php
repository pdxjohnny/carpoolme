<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function getNearBy($range){
	
	if(isset($_SESSION['nearby'])) unset($_SESSION['nearby']);

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


function getNearDest($range){
	
	if(isset($_SESSION['nearby'])) unset($_SESSION['nearby']);

	$table = "carpool_members";
	$myusername = $_SESSION['username'];
	$mytype = $_SESSION['type'];

	$mylatsub = $_SESSION['lat']-$range;
	$mylatadd = $_SESSION['lat']+$range;
	$mylngsub = $_SESSION['lng']-$range;
	$mylngadd = $_SESSION['lng']+$range;

	$mylatdsub = $_SESSION['latd']-$range;
	$mylatdadd = $_SESSION['latd']+$range;
	$mylngdsub = $_SESSION['lngd']-$range;
	$mylngdadd = $_SESSION['lngd']+$range;

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	
	/*
	echo "<script>console.log('about to query');</script>";
	echo "<script>console.log('lats and lngs');</script>";
	echo "<script>console.log('mylatsub:$mylatsub mylatsub:$mylatadd mylatsub:$mylngsub mylatsub:$mylngadd');</script>";
	echo "<script>console.log('latds and lngds');</script>";
	echo "<script>console.log('mylatsub:$mylatdsub mylatsub:$mylatdadd mylatsub:$mylngdsub mylatsub:$mylngdadd');</script>";
	*/

	$query = "SELECT username, latitude, longitude, type, dlatitude, dlongitude FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND dlatitude BETWEEN $mylatdsub AND $mylatdadd AND dlongitude BETWEEN $mylngdsub AND $mylngdadd AND NOT type = '$mytype' AND NOT username = '$myusername';";

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

	}

function makeMap($type){

if(0==strcmp($type,"dest")){?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="scripts/main.js"></script>
  <script type='text/javascript' src='http://code.jquery.com/jquery-1.6.2.js'></script>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="destform">
<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlongd" id="GPSlongd" type="hidden" value="">
    <div id="panel">
      <input id="address" type="textbox" value="Destination">
      <input type="button" value="Find" onclick="codeAddress('images/male.png')">
    </div>
<div id="mapholder"></div>
</form>
<script>
	var mylat = "<?php echo $_SESSION['lat']; ?>";
   	var mylng = "<?php echo $_SESSION['lng']; ?>";
	var mylatd = "<?php echo $_SESSION['latd']; ?>";
   	var mylngd = "<?php echo $_SESSION['lngd']; ?>";
	var locations = <?php echo json_encode($_SESSION['nearby']); ?>;
	makeMap(mylat,mylng,12,"mapholder");
	addPointMap(mylat,mylng,"You","images/male.png",1);
	addPointMap(mylatd,mylngd,"Your destination","images/mydest.png");
	arrayMap(locations,"images/car.png","images/dest.png");
</script>
<?php
	}
else if(0==strcmp($type,"nodest")){?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="scripts/main.js"></script>
  <script type='text/javascript' src='http://code.jquery.com/jquery-1.6.2.js'></script>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="destform">
<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlongd" id="GPSlongd" type="hidden" value="">
    <div id="panel">
      <input id="address" type="textbox" value="Destination">
      <input type="button" value="Find" onclick="codeAddress('images/male.png')">
    </div>
<div id="mapholder"></div>
</form>
<script>
	var mylat = "<?php echo $_SESSION['lat']; ?>";
   	var mylng = "<?php echo $_SESSION['lng']; ?>";
	var locations = <?php echo json_encode($_SESSION['nearby']); ?>;
	makeMap(mylat,mylng,12,"mapholder");
	addPointMap(mylat,mylng,"You","images/male.png",1);
	arrayMap(locations,"images/car.png");
</script>
<?php
	}
}

function setDest(){

if(isset($_POST['setDestB'])) {

	$whatlat = $_SESSION['latd'] = $_POST['GPSlatd'];
	$whatlng = $_SESSION['lngd'] = $_POST['GPSlongd'];
	$whatname = $_SESSION['username'];
	if((!$whatlat)||(!$whatlng)) exit ("<meta http-equiv='refresh' content='0'>");
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET dlatitude = $whatlat, dlongitude = $whatlng WHERE username='$whatname';");
		mysqli_close($con);
		return 0;
		}
	else{
		echo "<script>alert('Shit nigga it didn't work');</script>";
		return 1;
		}

	}
	}
?>
