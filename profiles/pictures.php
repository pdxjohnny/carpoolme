<?php

session_start();

$upload_dir = "/var/www/carpool/profiles/pictures/";
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$maxsize = 2000000;

if (	(0 != strcmp($extension,"jpg")) && 
	(0 != strcmp($extension,"png")) &&
	(0 != strcmp($extension,"gif"))    ) exit("Only jpg, png, and gif are allowed. ");

if ($_FILES["file"]["size"] < $maxsize){
	if ($_FILES["file"]["error"] > 0) {
		if(($_FILES["file"]["error"])==4) echo "No file selected";
		else echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
		}
	else {
		$ispic = 0;
		for($i = 0 ; $i < 3 ; $i++){
			if($i == 0)$picture = $upload_dir . $_SESSION['username'] . ".png";
			else if($i == 1)$picture = $upload_dir . $_SESSION['username'] . ".jpg";
			else if($i == 2)$picture = $upload_dir . $_SESSION['username'] . ".gif";
			if (file_exists($picture)) {
				unlink($picture);
				move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $_SESSION['username'] . '.' . $extension);
				exit ("Profile picture updated");
				$ispic = 1;
				} 
			}
		if($ispic == 0) {
			move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $_SESSION['username'] . '.' . $extension);
			echo "Profile picture uploaded";
			}
		}
	}
else{
	echo "Your picture is too large. ";
	}


?>
