<?php

	//$whatlat = $_SESSION['lat'];
	//$whatlng = $_SESSION['lng'];
	$whatname = "testuser";
	$whatpass = "pass123";
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	//$result = mysqli_query($con,"SELECT username FROM $table WHERE password='$whatpass'");

	$query = "SELECT username, latitude, longitude, type FROM $table";

if ($result = mysqli_query($con, $query)) {

    /* fetch associative array */
	$starter=0;
    while ($row = mysqli_fetch_row($result)) {
	$rowlength = count($row);
        //echo count($row) . $row[0] . $row[1] . $row[2] . $row[3] . "<br>";
	for($i = 0; $i < count($row); $i++){
		$_SESSION['nearby'][$starter][$i] = $row[$i];
		}
	$starter++;
    }
echo "There are " . count($_SESSION['nearby']) . " users near you.<br>";
echo "Each has " . count($_SESSION['nearby'][0]) . " data points.<br>";


	for($i = 0; $i < count($_SESSION['nearby']); $i ++){
		echo "Number $i : ";
		for($j = 0; $j < count($_SESSION['nearby'][$i]); $j++){
			echo $_SESSION['nearby'][$i][$j] . " ";
			}
		echo "<br>";
		}


    /*free result set */
    mysqli_free_result($result);
}


	echo "<br><br>";
	mysqli_close($con);

/*
	if(1 == mysqli_num_rows($result)){
		$_SESSION['username'] = $whatname;
		mysqli_query($con,"UPDATE $table SET latitude = $whatlat, longitude = $whatlng WHERE username='$whatname' AND password='$whatpass';");
		$_SESSION['nearby'] = array( ... );
		
		echo $_SESSION['username'] . " is now logged in" . "<meta http-equiv='refresh' content='0'>";
		}
	else{
		echo "Wrong username or password<br>";
		}
*/
?>
