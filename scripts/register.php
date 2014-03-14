<!--
Application: Carpoolme.net
File: Register
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

session_start();

//if(!defined('INCLUDE_CHECK')) die("INCLUDE_CHECK not defined<!--<script type='text/javascript'>history.go(-1);</script>-->");

if(0!=strcmp($_POST['password'],$_POST['confirmpassword'])) exit ($_POST['username'] . " your passwords do not match. ");

	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	$whatemail = $_POST['email'];
	$whattype = $_POST['type'];
	if((!$whatname)||(!$whatpass)||(!$whatemail)) exit ("$whatname please fill in all fields.");

	$lat = $_POST['mylat'];
	$lng = $_POST['mylng'];
	if((!$lng)||(!$lat)) exit ("$whatname please enable location.");

	if (filter_var($whatemail, FILTER_VALIDATE_EMAIL));
	else exit ("$whatname you have an invalid email. ");

	if ($_POST['cookie'] == true){
		setcookie("username",$whatname,time()+3600, "carpool.sytes.net");
		}

	$table="carpool_members"; // Table name 
	$whatname = strtolower($whatname);

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$whatname = mysqli_real_escape_string($con,$whatname);
	$whatpass = mysqli_real_escape_string($con,$whatpass);
	$whatemail = mysqli_real_escape_string($con,$whatemail);
	
	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname' OR email='$whatemail';");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_close($con);
		exit($whatname . " is already taken or email has already been used");
		}
	else{
		mysqli_query($con,"INSERT INTO $table (username,password,email,type,latitude,longitude) VALUES('$whatname','$whatpass','$whatemail','$whattype',$lat,$lng);");
		$row = mysqli_fetch_row(mysqli_query($con,"SELECT id FROM $table WHERE username='$whatname' OR email='$whatemail';"));
		$_SESSION['id'] = $row[0];
		$_SESSION['username'] = $whatname;
		$_SESSION['type'] = $whattype;
		file_put_contents("profiles/users", $whatname . "\n", FILE_APPEND);
		echo $_SESSION['username'] . " you are now logged in <meta http-equiv='refresh' content='0'>";
		}

	mysqli_close($con);
?>
