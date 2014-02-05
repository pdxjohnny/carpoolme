<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in<br>";
	require 'scripts/logout.php';
	require 'scripts/testphpfunctions.php';
?>
  <head>
    <title>Carpool</title>
  </head>
<h3>Hey <?php echo $_SESSION['username']; ?> you are here!</h3>
<?php
	require 'scripts/setLatestLeave.php';
	require 'scripts/setDest.php';
	if(0==strcmp($_SESSION['type'],"offer")){
		require 'scripts/seats.php';
		showSeats();
		inMyCar("offer");
		showMyCar("offer");
		wantMyCar();
		approveMyCar();
		}
	if($_SESSION['myride']){
		inMyCar("need");
		showMyCar("need");
		}
	if($_SESSION['latd']&&$_SESSION['lngd']){
		getNearDest(0.15);
		makeMap("dest");
		}
	else {
		getNearBy(0.15);
		makeMap("nodest");
		}
	require 'scripts/clearDest.php';
	require 'scripts/clearRide.php';
	require 'scripts/askForRide.php';
	}
else{
	require 'scripts/login.php';
	require 'scripts/register.php';
	}
?>
