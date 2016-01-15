<?php

$table = "carpool_members";

if(isset($_POST['string'])){
	echo updateString($table,$_POST['what'],$_POST['string'],$_POST['conditions']);
	}

else if(isset($_POST['num'])){
	echo updateNum($table,$_POST['what'],$_POST['num'],$_POST['conditions']);
	}

else if(isset($_POST['nullthis'])){
	echo updateNull($table,$_POST['nullthis'],$_POST['conditions']);
	}

else if(isset($_POST['get'])){
	echo get($table, $_POST['get'], $_POST['conditions'], (substr_count($_POST['get'], ',') +1) );
	}

else if(isset($_POST['theseNum'])){
	echo updateMultNum($table, $_POST['theseNum'], $_POST['newvalues'], $_POST['conditions']);
	}

else if(isset($_POST['theseString'])){
	echo updateMultString($table, $_POST['theseString'], $_POST['newvalues'], $_POST['conditions']);
	}

else echo "Not sure what to update. ";

function get($table,$stuff,$conditions,$howmany){

	// Create connection
	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		return "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT $stuff FROM $table WHERE $conditions;";

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

function updateString($table,$what,$with,$conditions){

	// Create connection
	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT username FROM $table WHERE $conditions;");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = '$with' WHERE $conditions;");
		mysqli_close($con);
		return "Updated";
		}
	else if(1 < mysqli_num_rows($result)){
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return $row[0] . " was not found. ";
		}
	}

function updateNum($table,$what,$with,$conditions){

	if (!is_numeric($with)) return "Not a number";

	// Create connection
	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT username FROM $table WHERE $conditions");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = $with WHERE $conditions;");
		mysqli_close($con);
		return "Updated";
		}
	else if(1 < mysqli_num_rows($result)){
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return $row[0] . " was not found. ";
		}
	}

function updateNull($table,$what,$conditions){

	// Create connection
	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT username FROM $table WHERE $conditions");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = NULL WHERE $conditions;");
		mysqli_close($con);
		return "Updated";
		}
	else if(1 < mysqli_num_rows($result)){
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == mysqli_num_rows($result)){
		mysqli_close($con);
		return $row[0] . " was not found. ";
		}
	}

function updateMultNum($table, $these, $newvalues, $conditions){
	
	//return json_encode($these) . ":" . json_encode($newvalues);
	if(count($these) != count($newvalues)) return "Updates don't match. ";

	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		return "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT id FROM $table WHERE $conditions;");
	$row_cnt = mysqli_num_rows($result);

	if(1 == $row_cnt){
		for($i = 0; $i < count($these); $i++ ){
			mysqli_query($con,"UPDATE $table SET $these[$i] = $newvalues[$i] WHERE $conditions;");
			}
		mysqli_close($con);
		return "Updated. ";
		}
	else if(1 < $row_cnt){
		mysqli_close($con);
		return "More than one user was found. ";
		}
	else if(0 == $row_cnt){
		mysqli_close($con);
		return $row[0] . " was not found. ";
		}
	}

function updateMultString($table, $these, $newvalues, $user){
	
	if(count($these) != count($newvalues)) return "Updates don't match. ";

	$con=mysqli_connect("localhost","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		return "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT id FROM $table WHERE username='$user';");
	$row_cnt = mysqli_num_rows($result);

	if(1 == $row_cnt){
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
		return $row[0] . " was not found. ";
		}
	}
?>
