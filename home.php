<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in.";
	require 'scripts/parts.php';
	require 'scripts/phpfunctions.php';
	logout("scripts/logout.php");
	help("help.php");
	includes("test");
	echo "<br><span id='returnSpan'></span>";

	setLatestLeave("scripts/setLatestLeave.php");
	echo "<br>";

	if(0==strcmp($_SESSION['type'],"offer")){
		seats("scripts/seats.php","scripts/seatsDisplay.php");
		echo "<br>";
		myCar("scripts/myCar.php");
		}
	myRide("scripts/myRide.php");
		
	if(isset($_SESSION['latd'])&&isset($_SESSION['lngd'])){
		getNearDest(0.15);
		setDest("scripts/setDest.php");
		makeMap("dest");
		}
	else {
		getNearBy(0.15);
		setDest("scripts/setDest.php");
		makeMap("nodest");
		}
	clearDest("scripts/clearDest.php");
	clearRide("scripts/clearRide.php");
	}
else {
	require 'scripts/login.php';
	require 'scripts/register.php';
	}
// Ask for ride is in main.js
?>
