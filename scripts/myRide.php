<?php

session_start();
require 'phpfunctions.php';

	if(get("incar",$_SESSION['username'])==NULL){
		if(get("ridingwith",$_SESSION['username'])==NULL) exit("none%You haven't asked anyone for a ride yet. ");
		else {
			$myride = get("ridingwith",$_SESSION['username']);
			exit("none%You haven't been approved to ride in $myride's car yet. ");
			}
		}
	else $myride = get("incar",$_SESSION['username']);

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
		echo json_encode($_SESSION['inmycarneed']) . "%You've been approved to ride in $myride's car. %$myride";
		}

	mysqli_close($con);

?>
