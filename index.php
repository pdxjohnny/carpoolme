<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in<br>";
	require 'scripts/logout.php';
	require 'scripts/phpfunctions.php';
	getNearBy(0.5);
?>
  <head>
    <title>Carpool</title>
  </head>
<h3>Hey <?php echo $_SESSION['username']; ?> you are here!</h3>
<?php
	latestLeave();
	makeMap();
	require 'scripts/setDest.php';
	getNearBy(0.15);
	showNearBy();
?>
  <head>
    <title>Carpool</title>
  </head>
<h3>Hey <?php echo $_SESSION['username']; ?> you are here!</h3>
<?php
	}
else{
	require 'scripts/login.php';
	require 'scripts/register.php';
	}
?>
