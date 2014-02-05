<?php

session_start();
require 'phpfunctions.php';

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");
echo "Seats ";

	$seats = $_SESSION['totalSeats'] = $_POST['seats'];
	if(!$seats) exit ("not updated. ");
	
	if(0 == updateNum("spots",$seats,$_POST['username'])) echo "updated. ";
	else echo "not updated. ";

?>
