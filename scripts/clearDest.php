<?php

session_start();
require 'phpfunctions.php';

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

	$whatname = $_SESSION['username'];
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET dlatitude = NULL, dlongitude = NULL WHERE username='$whatname';");
		mysqli_close($con);
		echo "Destination cleared. ";
		}
	else{
		echo "Failed to clear destination. ";
		}
	unset($_POST['clearDestB']);
	unset($_SESSION['latd']);
	unset($_SESSION['lngd']);

	getNearBy(0.15);
	makeMap("dest");
	
?>
