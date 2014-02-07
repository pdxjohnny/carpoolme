<!--
Application: Carpoolme.net
File: Login
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

session_start();

	$whatlat = $_SESSION['lat'] = $_POST['GPSlatl'];
	$whatlng = $_SESSION['lng'] = $_POST['GPSlngl'];
	$whatname = $_POST['usernamel'];
	$whatpass = $_POST['passwordl'];
	$whattype = $_POST['typel'];
	if((!$whatname)||(!$whatpass)||(!$whatlat)||(!$whatlng)) exit ("$whatname please fill in all fields and enable location. ");
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$whatname = mysqli_real_escape_string($con,$whatname);
	$whatpass = mysqli_real_escape_string($con,$whatpass);

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname' AND password='$whatpass';");
	
	if(1 == mysqli_num_rows($result)){
		$_SESSION['username'] = $whatname;
		$_SESSION['type'] = $whattype;
		mysqli_query($con,"UPDATE $table SET latitude = $whatlat, longitude = $whatlng, type = '$whattype' WHERE username='$whatname';");		

		if ($newresult = mysqli_query($con, "SELECT dlatitude, dlongitude, spots, ridingwith, incar, latestleave FROM $table WHERE username = '$whatname';")) {
	    		$row = mysqli_fetch_row($newresult);
			$_SESSION['latd'] = $row[0];
			$_SESSION['lngd'] = $row[1];
			$_SESSION['seats'] = $row[2];
			$_SESSION['myride'] = $row[3];
			$_SESSION['latestleave'] = $row[5];
			if($row[4]!=NULL) $_SESSION['myride'] = $row[4];
    			mysqli_free_result($newresult);
			echo $_SESSION['username'] . " is now logged in. <meta http-equiv='refresh' content='1'>";
   			}
    		mysqli_free_result($result);
		}
	else{
		echo "Wrong username or password. ";
		}

	mysqli_close($con);
?>
