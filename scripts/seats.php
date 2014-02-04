<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

	$whatname = $_SESSION['username'];
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

if(isset($_POST['seatsform'])) {

	$seats = $_SESSION['totalSeats'] = $_POST['seats'];
	if(!$seats) exit ("<meta http-equiv='refresh' content='0'>");
	
	updateNum("spots",$seats,$_SESSION['username']);
	}

else { ?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="seatsupdateform">
<?php if($_SESSION['seats']==NULL) echo "Seats Available: ";
else echo "Update Seats Available: "; ?>
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
