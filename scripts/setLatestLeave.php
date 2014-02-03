<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['setLatestLeave'])) {

	$whattime = $_SESSION['latestLeave'] = $_POST['datetime'];
	$whattime = mysql_real_escape_string($whattime);
	$whatname = $_SESSION['username'];
	if((!$whattime)) exit ("<meta http-equiv='refresh' content='0'>");
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET latestleave = '$whattime' WHERE username='$whatname';");
		}
	else{
		echo "<script>alert('Shit nigga it didn't work');</script>";
		}

	mysqli_close($con);
	}
?>
