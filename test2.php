<!--
Application: Carpoolme.net
File: Register
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php

$whatname = "test5";
		if(file_put_contents("profiles/users", $whatname . "\n", FILE_APPEND)) echo "good";
		else echo "bad" . file_put_contents("profiles/users", $whatname . "\n", FILE_APPEND);
?>
