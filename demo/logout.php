<?php

echo "Logging out..";

session_start();
session_destroy();
	
if(isset($_COOKIE['username'])){
	unset($_COOKIE['username']);
	setcookie('username', null, -1, '/');
	}

echo "<meta http-equiv='refresh' content='0'>";


?>
