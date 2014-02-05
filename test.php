<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in.";
	require 'test/parts.php';
	require 'test/phpfunctions.php';
	logout("test/logout.php");
	echo "<br>";
	includes("test");
	echo "<span id='returnSpan'></span>";
	echo "<br><span id='leavetime'></span><br>";

	setLatestLeave("test/setLatestLeave.php");
	if(0==strcmp($_SESSION['type'],"offer")){
		echo "<br>";
		seats("test/seats.php");
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
	clearDest("test/clearDest.php");
	clearRide("test/clearRide.php");
	require 'test/askForRide.php';
	}
else {
	require 'test/login.php';
	require 'test/register.php';
	}
?>
