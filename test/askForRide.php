<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['askride'])) {

	$myride = $_SESSION['myride'] = $_POST['myride'];
	$whatname = $_SESSION['username'];
	if(!$myride) exit ("<meta http-equiv='refresh' content='0'>");
	
	if(0==checkString("incar",$myride,$whatname)) updateString("ridingwith",$myride,$whatname);
	else echo "<script>alert('You are already riding with $myride.');</script>";

	echo "<meta http-equiv='refresh' content='0'>";
	}
?>
