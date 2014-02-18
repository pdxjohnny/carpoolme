<?php

if(isset($_POST['string'])){
	$what = $_POST['what'];
	$with = $_POST['string'];
	$user = $_POST['user'];
	updateString($what,$with,$user);
	}

if(isset($_POST['num'])){
	$what = $_POST['what'];
	$with = $_POST['num'];
	$user = $_POST['user'];
	updateNum($what,$with,$user);
	}

if(isset($_POST['null'])){
	$what = $_POST['what'];
	$user = $_POST['user'];
	updateNull($what,$user);
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

?>
