<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in.";
	require 'test/parts.php';
	require 'test/phpfunctions.php';
	logout("test/logout.php");
	help("test/help.php");
	includes("test");
	echo "<br><span id='returnSpan'></span>";

	setLatestLeave("test/setLatestLeave.php");
	echo "<br>";

	if(0==strcmp($_SESSION['type'],"offer")){
		seats("test/seats.php","test/seatsDisplay.php");
		echo "<br>";
		myCar("test/myCar.php");
		}
	myRide("test/myRide.php");
		
	if(isset($_SESSION['latd'])&&isset($_SESSION['lngd'])){
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
	}
else {
	require 'test/login.php';
	require 'test/register.php';
	}
// Ask for ride is in main.js
?>
