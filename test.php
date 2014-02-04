<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in<br>";
	require 'scripts/logout.php';
	require 'scripts/phpfunctions.php';
?>
  <head>
    <title>Carpool</title>
  </head>
<h3>Hey <?php echo $_SESSION['username']; ?> you are here!</h3>
<?php
	require 'scripts/setLatestLeave.php';
	require 'scripts/setDest.php';
	if(0==strcmp($_SESSION['type'],"offer")){
		echo "There are currently " . $_SESSION['seats'] . " seats avalable in your car.<br>";
		require 'scripts/seats.php';
		}
	if($_SESSION['latd']&&$_SESSION['lngd']){
		getNearDest(0.15);
		if(0==strcmp($_SESSION['type'],"offer")) makeMap("dest","walking");
		else makeMap("dest","cars");
		}
	else {
		getNearBy(0.15);
		if(0==strcmp($_SESSION['type'],"offer")) makeMap("nodest","walking");
		else makeMap("nodest","cars");
		}
	
	require 'scripts/clearDest.php';
	}
else{
	require 'scripts/login.php';
	require 'scripts/register.php';
	}
?>
