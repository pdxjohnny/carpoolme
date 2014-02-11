<?php

	$whatname = $_POST['tokick'];
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET ridingwith = NULL, incar = NULL WHERE username='$whatname';");
		echo "$whatname was kicked from your car. ";
		}
	else{
		echo "Failed to kick $whatname from your car. ";
		}
	mysqli_close($con);

?>
