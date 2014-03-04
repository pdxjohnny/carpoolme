<?php

if(isset($_POST['string'])){
	echo updateString($_POST['table'],$_POST['what'],$_POST['string'],$_POST['user']);
	}

else if(isset($_POST['num'])){
	echo updateNum($_POST['table'],$_POST['what'],$_POST['num'],$_POST['user']);
	}

else if(isset($_POST['null'])){
	echo updateNull($_POST['table'],$_POST['what'],$_POST['user']);
	}

else if(isset($_POST['get'])){
	echo get($_POST['table'], $_POST['get'], $_POST['something'], $_POST['isthis'], (substr_count($_POST['get'], ',') +1) );
	}

else if(isset($_POST['theseNum'])){
	echo updateMultNum($_POST['table'], $_POST['theseNum'], $_POST['newvalues'], $_POST['user']);
	}

else if(isset($_POST['theseString'])){
	echo updateMultString($_POST['table'], $_POST['theseString'], $_POST['newvalues'], $_POST['user']);
	}

else echo "Not sure what to update. ";

function get($table,$stuff,$something,$isthis,$howmany){

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		return "Failed to connect to MySQL: " . mysqli_connect_error();
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

function updateString($table,$what,$with,$user){

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
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return "$user was not found. ";
		}
	}

function updateNum($table,$what,$with,$user){

	if (!is_numeric($with)) return "Not a number";

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
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return "$user was not found. ";
		}
	}

function updateNull($table,$what,$user){

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
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return "$user was not found. ";
		}
	}

function updateMultNum($table, $these, $newvalues, $user){
	
	if(count($these) != count($newvalues)) return "Updates don't match. ";

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		return "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT id FROM $table WHERE username='$user';");

	if(1 == mysqli_num_rows($result)){
		for($i = 0; $i < count($these); $i++ ){
			mysqli_query($con,"UPDATE $table SET $these[$i] = $newvalues[$i] WHERE username='$user';");
			}
		mysqli_close($con);
		return "Updated. ";
		}
	else if(1 < mysqli_num_rows($result)){
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return "$user was not found. ";
		}
	}

function updateMultString($table, $these, $newvalues, $user){
	
	if(count($these) != count($newvalues)) return "Updates don't match. ";

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		return "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT id FROM $table WHERE username='$user';");

	if(1 == mysqli_num_rows($result)){
		for($i = 0; $i < count($these); $i++ ){
			mysqli_query($con,"UPDATE $table SET $these[$i] = '$newvalues[$i]' WHERE username='$user';");
			}
		mysqli_close($con);
		return "Updated. ";
		}
	else if(1 < mysqli_num_rows($result)){
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return "$user was not found. ";
		}
	}
?>
