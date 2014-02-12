<?php

session_start();
require 'phpfunctions.php';

if(isset($_POST['username'])){

	$picture = "profiles/pictures/" . $_POST['username'];
	$file = fopen($picture, "rb");
	if ($file) {
		echo $picture;
		} 
	else {
   		echo "none";
		}
	}

	echo "%";

if(isset($_POST['username'])){
	$file = "profiles/" . $_POST['username'];
	if (file_get_contents($file)) {
		echo file_get_contents($file);
		} 
	else {
   		echo "none";
		}
	}

	echo "%";

if(isset($_POST['userinfo'])){
	$file = 'profiles/' . $_POST['username'];
	if (file_put_contents($file, $_POST['userinfo'])) {
		echo "Updated your info. ";
		} 
	else {
   		echo "Couldn't update your info. ";
		}
	}

?>
