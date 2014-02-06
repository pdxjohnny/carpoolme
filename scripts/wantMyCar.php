<?php

session_start();
require 'phpfucntions.php';

	$wantcar = count($_SESSION['wantmycar']);
	if($wantcar==0) echo "There is no waiting to be approved for your car.<br>";
	else {
		if($wantcar==1) echo "There is one person who is waiting to be approved for your car.<br>";
		else echo "There are " . $wantcar . " people who are waiting to be approved for your car.<br>";
		echo "<form action=" . $_SERVER['PHP_SELF'] . " method='post' name='aprovalform'>";
		for($i = 0; $i < count($_SESSION['wantmycar']); $i ++){
			echo 'Person number ' . ($i+1) . ' is ' . $_SESSION['wantmycar'][$i];
			echo '<input type="checkbox" name="accept[]" value="' . $_SESSION['wantmycar'][$i] . '"><br>';
			}
		 echo '<input value="Accept" id="acceptgo" name="acceptgo" type="submit"></form>';
		}

?>
