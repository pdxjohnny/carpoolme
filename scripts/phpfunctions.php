<?php

function getNearBy(){
	
	$table="carpool_members";
	$templat = $_SESSION['lat'];
	$templng = $_SESSION['lng'];
	$myusername = $_SESSION['username'];

	$mylatsub = $templat-0.5;
	$mylatadd = $templat+0.5;
	$mylngsub = $templng-0.5;
	$mylngadd = $templng+0.5;

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT username, latitude, longitude, type FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND NOT username = '$myusername';";

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

?>
