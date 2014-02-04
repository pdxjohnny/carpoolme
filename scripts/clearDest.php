<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['clearDestB'])) {

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
		mysqli_query($con,"UPDATE $table SET dlatitude = NULL, dlongitude = NULL WHERE username='$whatname';");
		mysqli_close($con);
		return 0;
		}
	else{
		echo "<script>alert('Shit nigga it didn't work');</script>";
		return 1;
		}
	unset($_POST['clearDestB']);
	$_SESSION['latd']==NUll;
	$_SESSION['lngd']==NULL;
	echo "<meta http-equiv='refresh' content='0'>";
	}
else{?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="clearDestForm">
<input value="Clear Destination" id="clearDestB" name="clearDestB" type="submit">
</form>
<?php
	}
?>
