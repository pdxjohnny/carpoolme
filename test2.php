<!--
Application: Carpoolme.net
File: Register
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->
<?php
echo "starting<br>";

$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	
	$result = mysqli_query($con,"SELECT id FROM carpool_members WHERE username='rysmith';");

	echo mysqli_num_rows($result);

	mysqli_close($con);
?>
