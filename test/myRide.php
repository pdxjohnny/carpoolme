<?php

session_start();
require 'phpfunctions.php';

	$myride = $_SESSION['myride'];
	if($myride==NULL) exit("You haven't asked anyone for a ride. ");

	if(get("incar",$_SESSION['username'])==NULL) exit("You haven't been approved to ride in $myride's car yet. %$myride");

	$table = "carpool_members";
	$myusername = $_SESSION['username'];

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	if(isset($_SESSION['inmycarneed'])) unset($_SESSION['inmycarneed']);

	$query = "SELECT username FROM $table WHERE incar='$myride';";

	if ($result = mysqli_query($con, $query)) {
	    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
			$_SESSION['inmycarneed'][$i] = $row[0];
			 }
		mysqli_free_result($result);
		echo json_encode($_SESSION['inmycarneed']) . '%' . $myride;
		}

	mysqli_close($con);

?>
