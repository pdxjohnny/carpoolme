<?php

	define('INCLUDE_CHECK',true);
	require 'phpfunctions.php';
	$_SESSION['latestLeave'] = $_POST['datetime'];
	$_POST['datetime'] = mysql_real_escape_string($_POST['datetime']);
	if((!$_POST['datetime'])||(!$_POST['username'])) exit ("Username or datetime not defined");
	
	$worked = updateString("latestleave",$_POST['datetime'],$_POST['username']);
	if($worked==0) echo "Your leave time has been updated.";
	else echo "Your leave time failed to update.";

?>
