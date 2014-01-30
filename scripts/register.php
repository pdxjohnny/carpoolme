<?php

if(isset($_POST['reg'])) {

	$whatlat = $_SESSION['lat'] = $_POST['GPSlatr'];
	$whatlng = $_SESSION['lng'] = $_POST['GPSlongr'];
	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	$whatemail = $_POST['email'];
	if((!$whatname)&&(!$whatpass)&&(!$whatemail)) exit ("<meta http-equiv='refresh' content='0'>");
	$table="carpool_members"; // Table name 

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	mysqli_query($con,"INSERT INTO $table (username,password,email,latitude,longitude) VALUES('$whatname','$whatpass','$whatemail',$whatlat,$whatlng);");

	$_SESSION['username'] = $whatname;
	echo $_SESSION['username'] . " is now logged in" . "<meta http-equiv='refresh' content='0'>";

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
<input name="password" type="text"><br>
Email<br>
<input name="email" type="text"><br>
<input name="GPSlatr" id="GPSlatr" type="hidden" value="">
<input name="GPSlongr" id="GPSlongr" type="hidden" value="">
<input value="Register" id="reg" name="reg" type="submit"><br>
</form>

</html>
<?php
	}
?>
