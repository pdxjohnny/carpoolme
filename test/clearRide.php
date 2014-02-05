<?php

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");


	$whatname = $_SESSION['username'];
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
		mysqli_close($con);
		}
	else{
		echo "<script>alert('Shit nigga it didn't work');</script>";
		}
	unset($_POST['clearRide']);
	unset($_SESSION['myride']);
	unset($_SESSION['inmycar']);
	echo "<meta http-equiv='refresh' content='0'>";
	}
else{?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="clearRideForm">
<input value="Clear Ride" id="clearRide" name="clearRide" type="submit">
</form>
<?php
	}
?>
