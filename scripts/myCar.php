<?php

session_start();
require 'phpfunctions.php';

	$table = "carpool_members";
	$myusername = $_SESSION['username'];

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	if(isset($_SESSION['inmycaroffer'])) unset($_SESSION['inmycaroffer']);

	$query = "SELECT username, email FROM $table WHERE incar='$myusername' AND NOT username = '$myusername';";

	if ($result = mysqli_query($con, $query)) {

	    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
			$_SESSION['inmycaroffer'][$i] = $row[0];
   			 }
		mysqli_free_result($result);
		if(isset($_SESSION['inmycaroffer'])) echo json_encode($_SESSION['inmycaroffer']);
		else echo "none";
		}

	echo "%";

	$query = "SELECT username, email FROM $table WHERE ridingwith='$myusername' AND NOT username = '$myusername';";
	
	if(isset($_SESSION['wantmycar'])) unset($_SESSION['wantmycar']);

	if ($result = mysqli_query($con, $query)) {

	    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
			$_SESSION['wantmycar'][$i] = $row[0];
			 }
    		mysqli_free_result($result);
		if(isset($_SESSION['wantmycar'])) echo json_encode($_SESSION['wantmycar']);
		else echo "none";
		}

	mysqli_close($con);

	echo "%";
		
	if(!empty($_POST['accept'])) {
		$accept = $_POST['accept'];
		for($i=0; $i < count($accept); $i++){
			updateString("incar",$_SESSION['username'],$accept[$i]);
			updateNull("ridingwith",$accept[$i]);
			echo $accept[$i] . " was approved. ";
			}
		}

?>
