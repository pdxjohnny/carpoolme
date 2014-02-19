<?php

echo get("username","incar","pdxjohnny",1);

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
		return json_encode($res);
    		mysqli_free_result($result);
		mysqli_close($con);
		}	
	}
?>
