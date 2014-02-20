<?php

session_start();
require 'phpfunctions.php';
require 'parts.php';
//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

	$whatlat = $_SESSION['latd'] = $_POST['GPSlatd'];
	$whatlng = $_SESSION['lngd'] = $_POST['GPSlngd'];
	$whatname = $_POST['username'];

	if((!$whatlat)||(!$whatlng)||(!$whatname)) exit ("No destination given");
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
		echo "Destination updated";
		}
	else{
		echo "Destination failed to update";
		}

	mysqli_close($con);

	getNearDest(0.15);
	makeMap();
?>
