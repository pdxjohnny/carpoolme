<?php
	echo "Logging out..";
	session_start();
	session_destroy();
	echo "<meta http-equiv='refresh' content='0'>";
?>
