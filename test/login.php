<!--
Application: Carpoolme.net
File: Login
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

session_start();

	$whatlat = $_SESSION['lat'] = $_POST['GPSlat'];
	$whatlng = $_SESSION['lng'] = $_POST['GPSlng'];
	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	$whattype = $_POST['type'];
	if((!$whatname)||(!$whatpass)) exit ("$whatname please fill in all fields. ");
	else if((!$whatlat)||(!$whatlng)) exit ("$whatname please enable location. ");
	$table="carpool_test"; // Table name

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
		mysqli_query($con,"UPDATE $table SET lat = $whatlat, lng = $whatlng, type = '$whattype' WHERE username='$whatname';");
		mysqli_query($con,"UPDATE carpool_trip1 SET lat = $whatlat, lng = $whatlng WHERE username='$whatname';");	
		mysqli_query($con,"UPDATE carpool_trip2 SET lat = $whatlat, lng = $whatlng WHERE username='$whatname';");	
		mysqli_query($con,"UPDATE carpool_trip3 SET lat = $whatlat, lng = $whatlng WHERE username='$whatname';");	
		mysqli_query($con,"UPDATE carpool_trip4 SET lat = $whatlat, lng = $whatlng WHERE username='$whatname';");	
		mysqli_query($con,"UPDATE carpool_trip5 SET lat = $whatlat, lng = $whatlng WHERE username='$whatname';");	

		echo $_SESSION['username'] . " is now logged in. <meta http-equiv='refresh' content='1'>";
    		mysqli_free_result($result);
		}
	else{
		echo "Wrong username or password. ";
		}

	mysqli_close($con);
?>
