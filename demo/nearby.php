<?php

//session_start();

if(isset($_POST['latd'])){
	echo $_POST['latd'] . ":" . $_POST['lngd'];
/*	$found = false;
	$range = 0.15;
	while(!$found){
		$res = getNearDest($range);
		if($res != 1){
			echo json_encode($res);
			$found = true;
			break;
			}
		$range = $range + 0.15
		}
*/
	}

else if(isset($_POST['lat'])){
	echo $_POST['lat'] . ":" . $_POST['lng'];
/*	$found = false;
	$range = 0.15;
	while(!$found){
		$res = getNearBy($range);
		if($res != 1){
			echo json_encode($res);
			$found = true;
			break;
			}
		$range = $range + 0.15
		}
*/
	}

else{
	echo "nearby error<br>";
	}
/*
function getNearBy($range){
	
	if(isset($nearby)) unset($nearby);

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

	$query = "SELECT username, latitude, longitude, type FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND NOT username = '$myusername';";

	if ($result = mysqli_query($con, $query)) {

		$starter=0;
	    	while ($row = mysqli_fetch_row($result)) {
			for($i = 0; $i < count($row); $i++){
				$nearby[$starter][$i] = $row[$i];
				}
				$starter++;
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	if(isset($nearby)) return $nearby;
	else return 1;

	}

function getNearDest($range){
	
	if(isset($nearby)) unset($nearby);

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

	$query = "SELECT username, latitude, longitude, type, dlatitude, dlongitude, spots, availablespots, latestleave FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND dlatitude BETWEEN $mylatdsub AND $mylatdadd AND dlongitude BETWEEN $mylngdsub AND $mylngdadd AND (latestleave >= NOW() OR latestleave is NULL) AND NOT username = '$myusername';";

	if ($result = mysqli_query($con, $query)) {

		$starter=0;
	    	while ($row = mysqli_fetch_row($result)) {
			for($i = 0; $i < count($row); $i++){
				$nearby[$starter][$i] = $row[$i];
				}
				$starter++;
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	if(isset($nearby)) return $nearby;
	else return 1;

	}
*/
?>
