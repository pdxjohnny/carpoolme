<?php

if(isset($_POST['username'])){
$_POST['username'] = strtolower($_POST['username']);

	$ispic = 0;
	for($i = 0 ; $i < 3 ; $i++){
		if($i == 0)$picture = "pictures/" . $_POST['username'] . ".png";
		else if($i == 1)$picture = "pictures/" . $_POST['username'] . ".jpg";
		else if($i == 2)$picture = "pictures/" . $_POST['username'] . ".gif";
		if (file_exists($picture)) {
			echo "profiles/" . $picture;
			$ispic = 1;
			} 
		}
	if($ispic == 0) {
   		echo "none";
		}
	}

	echo "%";

if(isset($_POST['username'])){
	$file = "infos/" . $_POST['username'];
	if (file_exists($file)) {
		echo "exists";
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
