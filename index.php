<?php

session_start();

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in<br>";
	require 'scripts/logout.php';
	require 'scripts/phpfunctions.php';
	getNearBy();
	echo "Users near you<br>";
	showNearBy();
?>
<html>
  <head>
    <title>Carpool</title>
  </head>
<h3>Hey <?php echo $_SESSION['username']; ?> you are here!</h3>
<!----><script src="http://maps.google.com/maps/api/js?sensor=false"></script>

<div id="mapholder"></div>
<script src="scripts/main.js"></script>
<script>
	var mylat = "<?php echo $_SESSION['lat']; ?>";
   	var mylng = "<?php echo $_SESSION['lng']; ?>";
	oneOnMap(mylat,mylng);
</script>

</html>

<?php
	}
else{
	require 'scripts/login.php';
	require 'scripts/register.php';
	}
?>
