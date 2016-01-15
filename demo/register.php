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

if ( (!isset($_POST['password'])) || 
(!isset($_POST['confirmpassword'])) || 
(!isset($_POST['username'])) || 
(!isset($_POST['email'])) || 
(!isset($_POST['type'])) ||
(!isset($_POST["recaptcha_response_field"])) ||
(!isset($_POST["recaptcha_challenge_field"])) ) 
	exit ("$whatname please fill in all fields.");

require_once('recaptchalib.php');
	$publickey = "6LcPK_ISAAAAAEYXUJAsrhUNTXMfmPpnzc2AOA3i";
	$privatekey = "6LcPK_ISAAAAACglo1Id8fveDf45HHyHCj0Rp269";
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if (!($resp->is_valid)) {
		$error = $resp->error;
		exit("You entered the captcha wrong");
        	}


	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	$whatemail = $_POST['email'];
	$whattype = $_POST['type'];

	if(0!=strcmp($_POST['password'],$_POST['confirmpassword'])) exit ($_POST['username'] . " your passwords do not match. ");

	if (filter_var($whatemail, FILTER_VALIDATE_EMAIL));
	else exit ("$whatname you have an invalid email. ");

	$table="carpool_members"; // Table name 
	$whatname = strtolower($whatname);

	if ($_POST['cookie'] == true){
		setcookie("username",$whatname,time()+3600, "carpool.sytes.net");
		$_COOKIE['username'] = $whatname;
		}

	// Create connection
	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$whatname = mysqli_real_escape_string($con,$whatname);
	$whatpass = mysqli_real_escape_string($con,$whatpass);
	$whatemail = mysqli_real_escape_string($con,$whatemail);
	
	$result = mysqli_query($con,"SELECT id FROM $table WHERE username='$whatname' OR email='$whatemail';");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_close($con);
		exit($whatname . " is already taken or email has already been used");
		}
	else{
		mysqli_query($con,"INSERT INTO $table (username,password,email,type) VALUES('$whatname','$whatpass','$whatemail','$whattype');");
		$row = mysqli_fetch_row(mysqli_query($con,"SELECT id FROM $table WHERE username='$whatname';"));
		$_SESSION['id'] = $row[0];
		$_SESSION['username'] = $whatname;
		$_SESSION['type'] = $whattype;
		file_put_contents("profiles/users", $whatname . "\n", FILE_APPEND);
		echo $_SESSION['username'] . " you are now logged in <meta http-equiv='refresh' content='1'>";
		}

	mysqli_close($con);
?>
