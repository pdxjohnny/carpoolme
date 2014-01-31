<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['logingo'])) {

	$whatlat = $_SESSION['lat'] = $_POST['GPSlatl'];
	$whatlng = $_SESSION['lng'] = $_POST['GPSlongl'];
	$whatname = $_POST['username'];
	$whatpass = $_POST['password'];
	if((!$whatname)||(!$whatpass)||(!$whatlat)||(!$whatlng)) exit ("<script>alert('Yo $whatname please fill in all fields and enable location.');</script><meta http-equiv='refresh' content='0'>");
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$whatname' AND password='$whatpass'");
	
	if(1 == mysqli_num_rows($result)){
		$_SESSION['username'] = $whatname;
		mysqli_query($con,"UPDATE $table SET latitude = $whatlat, longitude = $whatlng WHERE username='$whatname' AND password='$whatpass';");		
		echo $_SESSION['username'] . " is now logged in" . "<meta http-equiv='refresh' content='0'>";
		}
	else{
		echo "Wrong username or password<br>";
		}

	mysqli_close($con);
	}

else{
?>
<html>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
navigator.geolocation.getCurrentPosition(function(position){ 
      	$('#GPSlatl').val(position.coords.latitude);
  	$('#GPSlongl').val(position.coords.longitude);
	});
</script>

<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="loginform">
<h4>Login</h4>
Username<br>
<input name='username' type="text"><br>
Password<br>
<input name="password" type="password"><br>
<input name="GPSlatl" id="GPSlatl" type="text" value="">
<input name="GPSlongl" id="GPSlongl" type="text" value="">
<input value="Login" id="logingo" name="logingo" type="submit"><br>
</form>
</html>
<?php
	}
?>
