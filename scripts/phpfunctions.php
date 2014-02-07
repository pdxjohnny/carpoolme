<?php

//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function get($what,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		$newresult = mysqli_query($con,"SELECT $what FROM $table WHERE username='$user';");
		$row = mysqli_fetch_row($newresult);
		$out = $row[0];
		mysqli_close($con);
		return $out;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return null;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return null;
		}
	}

function checkString($if,$is,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$check = mysqli_query($con,"SELECT $table WHERE $if = '$is' AND username='$user';");
	
	if(1 == mysqli_num_rows($check)){
		mysqli_close($con);
		return 0;
		}
	else{
		mysqli_close($con);
		return 1;
		}
	}

function updateString($what,$with,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = '$with' WHERE username='$user';");
		mysqli_close($con);
		return 0;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return 1;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return 1;
		}
	}

function updateNum($what,$with,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = $with WHERE username='$user';");
		mysqli_close($con);
		return 0;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return 1;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return 1;
		}
	}

function updateNull($what,$user){

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table WHERE username='$user'");
	
	if(1 == mysqli_num_rows($result)){
		mysqli_query($con,"UPDATE $table SET $what = NULL WHERE username='$user';");
		mysqli_close($con);
		return 0;
		}
	else if(1 < mysqli_num_rows($result)){
		echo "More than one user was found. ";
		mysqli_close($con);
		return 1;
		}
	else if(0 == mysqli_num_rows($result)){
		echo "$user was not found. ";
		mysqli_close($con);
		return 1;
		}
	}

function getNearBy($range){
	
	if(isset($_SESSION['nearby'])) unset($_SESSION['nearby']);

	$table = "carpool_members";
	$templat = $_SESSION['lat'];
	$templng = $_SESSION['lng'];
	$myusername = $_SESSION['username'];
	$mytype = $_SESSION['type'];

	$mylatsub = $templat-$range;
	$mylatadd = $templat+$range;
	$mylngsub = $templng-$range;
	$mylngadd = $templng+$range;

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT username, latitude, longitude, type FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND NOT username = '$myusername';";

	if ($result = mysqli_query($con, $query)) {

		$starter=0;
	    	while ($row = mysqli_fetch_row($result)) {
			for($i = 0; $i < count($row); $i++){
				$_SESSION['nearby'][$starter][$i] = $row[$i];
				}
				$starter++;
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	if(!$_SESSION['nearby']) getNearBy($range+0.05);
	}

function showNearBy(){

	for($i = 0; $i < count($_SESSION['nearby']); $i ++){
		echo "Number ";
		echo $i+1 . " : ";
		for($j = 0; $j < count($_SESSION['nearby'][$i]); $j++){
			echo $_SESSION['nearby'][$i][$j] . " ";
			}
		echo "<br>";
		}
	}


function getNearDest($range){
	
	if(isset($_SESSION['nearby'])) unset($_SESSION['nearby']);

	$table = "carpool_members";
	$myusername = $_SESSION['username'];
	$mytype = $_SESSION['type'];

	$mylatsub = $_SESSION['lat']-$range;
	$mylatadd = $_SESSION['lat']+$range;
	$mylngsub = $_SESSION['lng']-$range;
	$mylngadd = $_SESSION['lng']+$range;

	$mylatdsub = $_SESSION['latd']-$range;
	$mylatdadd = $_SESSION['latd']+$range;
	$mylngdsub = $_SESSION['lngd']-$range;
	$mylngdadd = $_SESSION['lngd']+$range;

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	
	/*
	echo "<script>console.log('about to query');</script>";
	echo "<script>console.log('lats and lngs');</script>";
	echo "<script>console.log('mylatsub:$mylatsub mylatsub:$mylatadd mylatsub:$mylngsub mylatsub:$mylngadd');</script>";
	echo "<script>console.log('latds and lngds');</script>";
	echo "<script>console.log('mylatsub:$mylatdsub mylatsub:$mylatdadd mylatsub:$mylngdsub mylatsub:$mylngdadd');</script>";
	*/

	$query = "SELECT username, latitude, longitude, type, dlatitude, dlongitude, spots, availablespots, latestleave FROM $table WHERE latitude BETWEEN $mylatsub AND $mylatadd AND longitude BETWEEN $mylngsub AND $mylngadd AND dlatitude BETWEEN $mylatdsub AND $mylatdadd AND dlongitude BETWEEN $mylngdsub AND $mylngdadd AND NOT username = '$myusername';";

	if ($result = mysqli_query($con, $query)) {

		$starter=0;
	    	while ($row = mysqli_fetch_row($result)) {
			for($i = 0; $i < count($row); $i++){
				$_SESSION['nearby'][$starter][$i] = $row[$i];
				}
				$starter++;
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	}

function makeMap($dest){

	if(0==strcmp($dest,"dest")){?>
<script>
	var mylat = "<?php echo $_SESSION['lat']; ?>";
   	var mylng = "<?php echo $_SESSION['lng']; ?>";
	var mylatd = "<?php echo $_SESSION['latd']; ?>";
   	var mylngd = "<?php echo $_SESSION['lngd']; ?>";
	var locations = <?php echo json_encode($_SESSION['nearby']); ?>;
	makeMap(mylat,mylng,12,"mapholder");
	addPointMap(mylat,mylng,"You","images/male.png",1);
	addPointMap(mylatd,mylngd,"Your destination","images/mydest.png");
	arrayMap(locations);
</script>
<?php
	}
else if(0==strcmp($dest,"nodest")){?>
<script>
	var mylat = "<?php echo $_SESSION['lat']; ?>";
   	var mylng = "<?php echo $_SESSION['lng']; ?>";
	var locations = <?php echo json_encode($_SESSION['nearby']); ?>;
	makeMap(mylat,mylng,12,"mapholder");
	addPointMap(mylat,mylng,"You","images/male.png",1);
	arrayMap(locations);
</script>
<?php
		}
	}

function inMyCar($type){

	$table = "carpool_members";
	$myusername = $_SESSION['username'];
	$myride = $_SESSION['myride'];

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	if(0==strcmp($type,"offer")){
		if(isset($_SESSION['inmycaroffer'])) unset($_SESSION['inmycaroffer']);
		$query = "SELECT username FROM $table WHERE incar='$myusername' AND NOT username = '$myusername';";

		if ($result = mysqli_query($con, $query)) {

		    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
				$_SESSION['inmycaroffer'][$i] = $row[0];
   				 }

    			mysqli_free_result($result);
			}
		}	
	else if(0==strcmp($type,"need")){
		if(isset($_SESSION['inmycarneed'])) unset($_SESSION['inmycarneed']);
		$query = "SELECT username FROM $table WHERE incar='$myride';";

		if ($result = mysqli_query($con, $query)) {

		    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
				$_SESSION['inmycarneed'][$i] = $row[0];
   				 }

    			mysqli_free_result($result);
			}
		}	

	mysqli_close($con);

	}

function wantMyCar(){

	$table = "carpool_members";
	$myusername = $_SESSION['username'];

	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$query = "SELECT username FROM $table WHERE ridingwith='$myusername' AND NOT username = '$myusername';";
	
	if(isset($_SESSION['wantmycar'])) unset($_SESSION['wantmycar']);

	if ($result = mysqli_query($con, $query)) {

	    	for ($i = 0;$row = mysqli_fetch_row($result);$i++) {
				$_SESSION['wantmycar'][$i] = $row[0];
   			 }

    		mysqli_free_result($result);
		}

	mysqli_close($con);

	}

function showMyCar($type){

	if(0==strcmp($type,"offer")){
		$incar = count($_SESSION['inmycaroffer']);
		if($incar==0) echo "There is no one in your car.<br>";
		else {
			if($incar==1) echo "There is one person in your car.<br>";
			else echo "There are " . $incar . " people in your car.<br>";
			for($i = 0; $i < count($_SESSION['inmycaroffer']); $i ++){
				echo "Person number " . ($i+1) . " is " . $_SESSION['inmycaroffer'][$i] . "<br>";
				}
			}
		}
	else {
		if(get("incar",$_SESSION['username'])==NULL) echo "You haven't been approved to ride in " . $_SESSION['myride'] . "'s car yet.<br>";
		else{
			$incar = count($_SESSION['inmycarneed']);
			echo "You're riding with  " . $_SESSION['myride'] . " there are " . ($incar+1) . " people in your car.<br>";
			for($i = 0; $i < count($_SESSION['inmycarneed']); $i ++){
				if($_SESSION['username']==$_SESSION['inmycarneed'][$i]) echo "Person number " . ($i+2) . " is you (" . $_SESSION['inmycarneed'][$i] . ")<br>";
				else echo "Person number " . ($i+2) . " is " . $_SESSION['inmycarneed'][$i] . "<br>";
				}
			}
		}
	}

function approveMyCar($type){

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
	}

function getSeats(){

	$whatname  = $_SESSION['username'];
	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT username FROM $table WHERE ridingwith='$whatname' AND NOT username = '$whatname';");
	$_SESSION['numberWantSeats'] = mysqli_num_rows($result);

	$result = mysqli_query($con,"SELECT username FROM $table WHERE incar='$whatname' AND NOT username = '$whatname';");
	$_SESSION['numberApprovedSeats'] = mysqli_num_rows($result);
	
	$_SESSION['totalSeats'] = get("spots",$whatname);
	$_SESSION['numberavailableSeats'] = $_SESSION['totalSeats']-$_SESSION['numberApprovedSeats'];
	updateNum("availablespots",$_SESSION['numberavailableSeats'],$whatname);

	mysqli_close($con);
	
	}

/*
function showSeats(){

	if($_SESSION['numberavailableSeats']>1) echo "There are currently " . $_SESSION['numberavailableSeats'] . " seats avalable in your car.<br>";
	else if($_SESSION['numberavailableSeats']==1) echo "There is currently " . $_SESSION['numberavailableSeats'] . " seat avalable in your car.<br>";
	else if($_SESSION['numberavailableSeats']==0) echo "There are currently no seats avalable in your car.<br>";
	}*/
?>
