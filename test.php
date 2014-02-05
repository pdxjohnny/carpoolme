<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in.";
	require 'test/parts.php';
	includes();
	require 'test/logout.php';
	require 'test/phpfunctions.php';
	echo "<span id='returnSpan'></span>";
?>
<h3>Hey <?php echo $_SESSION['username']; ?> you are here!</h3>
<?php
	setLatestLeave("test/setLatestLeave.php");
	if(0==strcmp($_SESSION['type'],"offer")){
		require 'test/seats.php';
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
		setDest("test/setDest.php");
		makeMap("dest");
		}
	else {
		getNearBy(0.15);
		setDest("test/setDest.php");
		makeMap("nodest");
		}
	require 'test/clearDest.php';
	require 'test/clearRide.php';
	require 'test/askForRide.php';
	}
else {
	require 'test/login.php';
	require 'test/register.php';
	}
?>
