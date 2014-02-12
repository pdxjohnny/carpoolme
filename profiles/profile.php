<?php

if(isset($_POST['username'])){

	$picture = "pictures/" . $_POST['username'];
	if (file_exists($picture)) {
		echo $picture;
		} 
	else {
   		echo "none";
		}
	}

	echo "%";

if(isset($_POST['username'])){
	$file = "infos/" . $_POST['username'];
	if (file_exists($file)) {
		echo file_get_contents($file);
		} 
	else {
   		echo "none";
		}
	}

	echo "%";

if(isset($_POST['userinfo'])){
	$file = 'infos/' . $_POST['username'];
	if (file_put_contents($file, $_POST['userinfo'])) {
		echo "Updated your info. ";
		} 
	else {
   		echo "Couldn't update your info. ";
		}
	}

?>
