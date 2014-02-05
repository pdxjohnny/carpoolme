<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['reg'])) {

	$whatlat = $_SESSION['lat'] = $_POST['GPSlatr'];
	$whatlng = $_SESSION['lng'] = $_POST['GPSlongr'];
	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	$whatemail = $_POST['email'];
	$whattype = $_POST['type'];
	if (!filter_var($whatemail, FILTER_VALIDATE_EMAIL));
	else echo "<script>alert('$whatname you have an invalid email.');</script><meta http-equiv='refresh' content='0'>";

	if((!$whatname)||(!$whatpass)||(!$whatemail)||(!$whatlat)||(!$whatlng)) exit ("<script>alert('$whatname please fill in all fields and enable location.');</script><meta http-equiv='refresh' content='0'>");
	$table="carpool_members"; // Table name 

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$whatname = mysqli_real_escape_string($con,$whatname);
	$whatpass = mysqli_real_escape_string($con,$whatpass);
	$whatemail = mysqli_real_escape_string($con,$whatemail);
	
	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname' OR email='$whatemail';");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_close($con);
		exit($whatname . " is already taken");
		}
	else{
		mysqli_query($con,"INSERT INTO $table (username,password,email,type,latitude,longitude) VALUES('$whatname','$whatpass','$whatemail','$whattype',$whatlat,$whatlng);");
		$_SESSION['username'] = $whatname;
		$_SESSION['type'] = $whattype;
		echo $_SESSION['username'] . " is now logged in" . "<meta http-equiv='refresh' content='0'>";
		}

	mysqli_close($con);
	}

else{
?>
<html>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
navigator.geolocation.getCurrentPosition(function(position){ 
      	$('#GPSlatr').val(position.coords.latitude);
  	$('#GPSlongr').val(position.coords.longitude);
	});
</script>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="registerfrom">
<h4>Register</h4>
Username<br>
<input name='username' type="text"><br>
Password<br>
<input name="password" type="password"><br>
Email<br>
<input name="email" type="text"><br>
<select id="typer" name="type">
  <option value="need">Need Ride</option>
  <option value="offer">Offering Ride</option>
</select>
<input name="GPSlatr" id="GPSlatr" type="hidden" value="">
<input name="GPSlongr" id="GPSlongr" type="hidden" value="">
<input value="Register" id="reg" name="reg" type="submit"><br>
</form>

</html>
<?php
	}
?>
