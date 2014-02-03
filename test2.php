<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in<br>";
	require 'scripts/logout.php';
	require 'scripts/phpfunctions.php';
	if($_SESSION['latd']&&$_SESSION['lngd']) getNearDest(0.15);
	else getNearBy(0.15);
	}
else{
	require 'scripts/login.php';
	require 'scripts/register.php';
	}
?>
