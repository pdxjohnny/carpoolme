<?php

session_start();
define('INCLUDE_CHECK',true);
require 'phpfunctions.php';

if(isset($_POST['getLeaveTime'])){
	$time = get("latestleave",$_SESSION['username']);
	exit ($time);
	}

	$_SESSION['latestLeave'] = $_POST['datetime'];
	$_POST['datetime'] = mysql_real_escape_string($_POST['datetime']);
	if((!$_POST['datetime'])||(!$_SESSION['username'])) exit ("Username or datetime not defined. ");
	
	$worked = updateString("latestleave",$_POST['datetime'],$_SESSION['username']);
	if($worked==0) echo "Your leave time has been updated.";
	else echo "Your leave time failed to update.";


?>
