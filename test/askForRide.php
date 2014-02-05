<?php

session_start();
require 'phpfunctions.php';

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

	$myride = $_SESSION['myride'] = $_POST['myride'];
	$whatname = $_SESSION['username'];
	if(!$myride) exit ("User to ride with not set. ");
	
	if(1==checkString("incar",$myride,$whatname)){
		updateString("ridingwith",$myride,$whatname);
		echo "Sent $myride a request to be in their car. ";
		inMyCar("need");
		}
	else echo "You are already riding with $myride. ";


?>
