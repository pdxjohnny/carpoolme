<?php

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

session_start();

	if(!isset($_SESSION['myride'])) exit ("You aren't riding with anyone. ");

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
		mysqli_query($con,"UPDATE $table SET ridingwith = NULL, incar = NULL WHERE username='$whatname';");
		echo "You were removed from " . $_SESSION['myride'] . "'s car. ";
		}
	else{
		echo "Failed to remove you from " . $_SESSION['myride'] . "'s car. ";
		}
	mysqli_close($con);
	unset($_SESSION['myride']);
	unset($_SESSION['inmycarneed']);
?>
