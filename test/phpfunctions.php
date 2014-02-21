<?php

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function get($what,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		$newresult = mysqli_query($con,"SELECT $what FROM $table WHERE username='$user';");
		$row = mysqli_fetch_row($newresult);
		$out = $row[0];
		mysqli_close($con);
		return $out;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return null;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return null;
		}
	}

function checkString($if,$is,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$check = mysqli_query($con,"SELECT username FROM $table WHERE $if = '$is' AND username='$user';");
	
	if(1 == mysqli_num_rows($check)){
		mysqli_close($con);
		return 0;
		}
	else{
		mysqli_close($con);
		return 1;
		}
	}

function updateString($what,$with,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = '$with' WHERE username='$user';");
		mysqli_close($con);
		return 0;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return 1;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return 1;
		}
	}

function updateNum($what,$with,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = $with WHERE username='$user';");
		mysqli_close($con);
		return 0;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return 1;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return 1;
		}
	}

function updateNull($what,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = NULL WHERE username='$user';");
		mysqli_close($con);
		return 0;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return 1;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return 1;
		}
	}

function inMyCar($type){

	$table = "carpool_members";
	$myusername = $_SESSION['username'];
	$myride = $_SESSION['myride'];

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	if(0==strcmp($type,"offer")){
		if(isset($_SESSION['inmycaroffer'])) unset($_SESSION['inmycaroffer']);
		$query = "SELECT username FROM $table WHERE incar='$myusername' AND NOT username = '$myusername';";

		if ($result = mysqli_query($con, $query)) {

		    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
				$_SESSION['inmycaroffer'][$i] = $row[0];
   				 }

    			mysqli_free_result($result);
			}
		}	
	else if(0==strcmp($type,"need")){
		if(isset($_SESSION['inmycarneed'])) unset($_SESSION['inmycarneed']);
		$query = "SELECT username FROM $table WHERE incar='$myride';";

		if ($result = mysqli_query($con, $query)) {

		    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
				$_SESSION['inmycarneed'][$i] = $row[0];
   				 }

    			mysqli_free_result($result);
			}
		}	

	mysqli_close($con);

	}

function wantMyCar(){

	$table = "carpool_members";
	$myusername = $_SESSION['username'];

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT username FROM $table WHERE ridingwith='$myusername' AND NOT username = '$myusername';";
	
	if(isset($_SESSION['wantmycar'])) unset($_SESSION['wantmycar']);

	if ($result = mysqli_query($con, $query)) {

	    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
				$_SESSION['wantmycar'][$i] = $row[0];
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	}

function getSeats(){

	$whatname  = $_SESSION['username'];
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT username FROM $table WHERE ridingwith='$whatname' AND NOT username = '$whatname';");
	$_SESSION['numberWantSeats'] = mysqli_num_rows($result);

	$result = mysqli_query($con,"SELECT username FROM $table WHERE incar='$whatname' AND NOT username = '$whatname';");
	$_SESSION['numberApprovedSeats'] = mysqli_num_rows($result);
	
	$_SESSION['totalSeats'] = get("spots",$whatname);
	$_SESSION['numberavailableSeats'] = $_SESSION['totalSeats']-$_SESSION['numberApprovedSeats'];
	updateNum("availablespots",$_SESSION['numberavailableSeats'],$whatname);

	mysqli_close($con);
	
	}

?>
