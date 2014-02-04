<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['seatsform'])) {

	$seats = $_POST['seats'];
	$whatname = $_SESSION['username'];
	if(!$seats) exit ("<meta http-equiv='refresh' content='0'>");
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET spots = $seats WHERE username='$whatname';");
		$_SESSION['seats'] = $seats;
		}
	else{
		echo "<script>alert('Shit nigga it didn't work');</script>";
		}

	mysqli_close($con);
	echo "<meta http-equiv='refresh' content='0'>";
	}

else{?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="seatsupdateform">
<?php if($_SESSION['seats']==NULL) echo "Seats Avalable: ";
else echo "Update Seats Avalable: "; ?>
<select name="seats" id="seats">
<script>
for(var i = 1;i<=10;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<?php if($_SESSION['seats']==NULL) echo '<input value="Set" id="seatsform" name="seatsform" type="submit"><br>';
else echo '<input value="Update" id="seatsform" name="seatsform" type="submit"><br>'; ?>
</div>
</form>

<?php
	}
?>
