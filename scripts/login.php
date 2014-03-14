<!--
Application: Carpoolme.net
File: Login
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

session_start();

	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	$whattype = $_POST['type'];
	if((!$whatname)||(!$whatpass)) exit ("$whatname please fill in all fields. ");

	if (0==strcmp($_POST['cookie'], "on")){
		setcookie("username",$whatname,time()+3600, "carpool.sytes.net");
		}

	// Table name
	$table="carpool_members"; 
	$whatname = strtolower($whatname);
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
		mysqli_query($con,"UPDATE $table SET type = '$whattype' WHERE username='$whatname';");		

		if ($newresult = mysqli_query($con, "SELECT id FROM $table WHERE username = '$whatname';")) {
	    		$row = mysqli_fetch_row($newresult);
			$_SESSION['id'] = $row[0];
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
