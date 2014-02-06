<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in.";
	require 'test/parts.php';
	require 'test/phpfunctions.php';
	logout("test/logout.php");
	includes("test");
	echo "<br><span id='returnSpan'></span>";
	echo "<span id='leavetime'><br></span><br>";
	setLatestLeave("test/setLatestLeave.php");
	echo "<br>";

	if(0==strcmp($_SESSION['type'],"offer")){
		seats("test/seats.php","test/seatsDisplay.php");
		echo "<br>";
		myCar("test/myCar.php");
		}
	if($_SESSION['myride']){
		// Below should be in ajax requested function
			// myRide();
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
	clearDest("test/clearDest.php");
	clearRide("test/clearRide.php");
	help("test/help.php");
	}
else {
	require 'test/login.php';
	require 'test/register.php';
	}
// Ask for ride is in main.js
?>
