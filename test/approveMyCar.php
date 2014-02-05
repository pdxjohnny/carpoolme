<?php

session_start();
require 'phpfunctions.php';

$accept = $_POST['accept'];
	if(empty($accept)) {
		echo "No one was approved. <meta http-equiv='refresh' content='0'>";
		} 
	else{
		for($i=0; $i < count($accept); $i++){
			updateString("incar",$_SESSION['username'],$accept[$i]);
			updateNull("ridingwith",$accept[$i]);
			}
		}
	echo "<meta http-equiv='refresh' content='0'>";

?>
