<?php

if(isset($_POST['string'])){
	$what = $_POST['what'];
	$with = $_POST['string'];
	$user = $_POST['user'];
	echo updateString($what,$with,$user);
	}

else if(isset($_POST['num'])){
	$what = $_POST['what'];
	$with = $_POST['num'];
	$user = $_POST['user'];
	echo updateNum($what,$with,$user);
	}

else if(isset($_POST['null'])){
	$what = $_POST['what'];
	$user = $_POST['user'];
	echo updateNull($what,$user);
	}

else if(isset($_POST['get'])){
	$stuff = $_POST['get'];
	$something = $_POST['something'];
	$howmany = $_POST['howmany'];
	$isthis = $_POST['isthis'];
	echo get($stuff,$something,$isthis,$howmany);
	}

else echo "Not sure what to update. ";

function get($stuff,$something,$isthis,$howmany){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT $stuff FROM $table WHERE $something='$isthis';";

	if ($result = mysqli_query($con, $query)) {
	   	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
	   		for ($j = 0 ; $j < $howmany ; $j++) {
				$res[$i][$j] = $row[$j];
   				 }
   			 }
		if(isset($res)) return json_encode($res);
		else return "none";
    		mysqli_free_result($result);
		mysqli_close($con);
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
		return "Updated";
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

	if (!is_numeric($with)) return "Not a number";

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
		return "Updated";
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
		return "Updated";
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
