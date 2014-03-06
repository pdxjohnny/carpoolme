<!--
Application: Carpoolme.net
File: Home page
Date: 2/6/14
Author: John Andersen
(c) Copyright 2014 All rights reserved
-->

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title>Carpoolme</title>
	<meta name="description" content="Carpoolme">
	<meta name="author" content="John Andersen">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="stylesheets/base.css">
	<link rel="stylesheet" href="stylesheets/skeleton.css">
	<link rel="stylesheet" href="stylesheets/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<link rel="apple-touch-icon" href="images/apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">

</head>
<body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function hideAll(){
	$('#toggleMap').hide();
	$('#toggleMyTrips').hide();
	$('#toggleProfile').hide();
	$('#help').hide();
	}
function toggleMap(){
	hideAll();
	$('#toggleMap').show();
	}
function toggleMyTrips(){
	hideAll();
	$('#toggleMyTrips').show();
	}
function toggleProfile(){
	hideAll();
	$('#toggleProfile').show();
	}
function toggleHelp(){
	$('#help').html(readFile("help.php"));
	event.preventDefault();
	hideAll();
	$('#help').show();
	}
</script>


	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	<div class="container">
		<div class="sixteen columns remove-bottom">
			<h1 class="remove-bottom" style="margin-top: 40px">Carpoolme</h1>
			<h5 class="remove-bottom" >Beta v1.1 <a href="#" data-mailto="carpoolme.net@gmail.com">Report Bug</a></h5>
<?php

session_start();
define('INCLUDE_CHECK',true);
$dir = "scripts";
require $dir . '/parts.php';


if(isset($_SESSION['username'])){
	echo "User " . $_SESSION['username'] . " is logged in.";
	echo '
<select id="type" >
  <option value="need">Need Ride</option>
  <option value="offer">Offering Ride</option>
</select>
';
	logout($dir . "/logout.php");
	includes($dir);
?>
			<br><center><span style="color: #4593C4; margin-top:10px;" id='returnSpan'></span></center>
			<hr style="remove-bottom" />
		</div>
		<div class="sixteen columns remove-bottom" style="margin-top: 10px; margin-bottom: 10px;">
			<center>
			<button class="remove-bottom" onclick="toggleMap();">Map</button>
			<button class="remove-bottom" onclick="toggleMyTrips();">My Trips</button>
			<button class="remove-bottom" onclick="toggleProfile();">Profiles</button>
			<button class="remove-bottom" onclick="toggleHelp();">Help</button>
			</center>
			<hr style="margin-bottom: 10px;"/>
		</div>
		<div id="toggleMap" class="sixteen columns remove-bottom">
<?php
	setDest($dir . "/setDest.php");
	clearDest($dir . "/clearDest.php");
	clearRide($dir . "/clearRide.php");
?>
		</div>
		<div id="toggleMyTrips" style="display:none;" class="sixteen columns remove-bottom">
			<div id="leaveSeatsMpg" class="five columns ">
<?php
		setLatestLeave($dir . "/setLatestLeave.php");
		seats($dir . "/seats.php",$dir . "/seatsDisplay.php");
		mpg($dir . "/seats.php",$dir . "/seatsDisplay.php");
		
?>
			</div>
			<div id="myCar" class="five columns ">
<?php
		myCar($dir . "/myCar.php");
?>
			</div>
			<div id="myRide" class="five columns ">
<?php
	myRide($dir . "/myRide.php");
?>
			</div>
		</div>
		<div id="toggleProfile" style="display:none;" class="sixteen columns remove-bottom">
<?php
	myProfile("profiles/profile.php");
?>
		</div>
		<div id="help" style="display:none;" class="sixteen columns remove-bottom">
		</div>
<?php
	}

/*------------------------------------ Not Logged In ----------------------------------------------------*/
// Not Logged in
else {
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="scripts/demo.js"></script>
	<a href="#" id="toggleLogin" >Login</a>
	<a href="#" id="toggleRegister" >Register</a>
	<a href="#" id="toggleDemo" >Demo</a>
	<center><span style="color: #4593C4; margin-top:10px;" id='returnSpan'></span></center>
	<hr />
	<div id="logindiv">
		<div style="display: table; margin: 0 auto;">
<?php
	login($dir . "/login.php");
?>
		</div>
	</div>
	<div id="registerdiv" style="display:none;" >
		<div style="display: table; margin: 0 auto;">
<?php
	register($dir . "/register.php");
?>	
		</div>
	</div>
	<div id="demodiv" style="display:none;" class="sixteen columns remove-bottom" >
		<div class="sixteen columns remove-bottom" style="margin-top: 10px; margin-bottom: 10px;">
			<center>
			<button class="remove-bottom" onclick="toggleMap();">Map</button>
			<button class="remove-bottom" onclick="toggleMyTrips();">My Trips</button>
			<button class="remove-bottom" onclick="toggleHelp();">Help</button>
			</center>
			<hr style="margin-bottom: 10px;"/>
		</div>
		<div id="toggleMap" class="sixteen columns remove-bottom">
		<center><h2>Demo coming soon</h2></center>
		</div>
		<div id="toggleMyTrips" style="display:none;" class="sixteen columns remove-bottom">
		<center><h2>Demo coming soon</h2></center>
		</div>
		<div id="help" style="display:none;" class="sixteen columns remove-bottom">
		</div>
	</div>
<?php
	}
// Ask for ride is in main.js
?>	
	</center></div>
	</div><!-- container -->


<!-- End Document
================================================== -->
</body>
<script>
$('a[data-mailto]').click(function(){
	var link = 'scripts/mailto.html#mailto:johnandersenpdx@gmail.com?subject=Carpoolme.net Bug&body=There was a bug in the Carpoolme.net site.';
	window.open(link, 'Mailer');
	return false;
	});

$('#toggleRegister').click(function(){
	$('#registerdiv').show();
	$('#logindiv').hide();
	$('#demodiv').hide();
	});

$('#toggleLogin').click(function(){
	$('#registerdiv').hide();
	$('#demodiv').hide();
	$('#logindiv').show();
	});

$('#toggleDemo').click(function(){
	$('#registerdiv').hide();
	$('#logindiv').hide();
	$('#demodiv').show();
	});
</script>
</html>
