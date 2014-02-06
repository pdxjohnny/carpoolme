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
	<meta name="description" content="">
	<meta name="author" content="">

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
function toggleMap(){
	$('#toggleMap').show();
	$('#toggleCar').hide();
	$('#help').hide();
	}
function toggleCar(){
	$('#toggleMap').hide();
	$('#toggleCar').show();
	$('#help').hide();
	}
function toggleHelp(){
	$.ajax({
		type: "GET",
		url: "help.php",
		data: {},
		success: function(data){
			$('#help').html(data);
			}
		});
	event.preventDefault();
	$('#toggleMap').hide();
	$('#toggleCar').hide();
	$('#help').show();
	}
</script>


	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	<div class="container">
		<div class="sixteen columns remove-bottom">
			<h1 class="remove-bottom" style="margin-top: 40px">Carpoolme</h1>
			<h5>Beta v1 <a href="#" data-mailto="johnandersenpdx@gmail.com">Report Bug</a></h5><br>
<?php

session_start();
define('INCLUDE_CHECK',true);

if($_SESSION['username']!=NULL){
	echo "User " . $_SESSION['username'] . " is logged in.";
	require 'scripts/parts.php';
	require 'scripts/phpfunctions.php';
	logout("scripts/logout.php");
	includes("scripts");
?>
			<br><center><span style="color: #4593C4; margin-top:10px;" id='returnSpan'></span></center>
			<hr style="remove-bottom" />
		</div>
		<div class="sixteen columns remove-bottom" style="margin-top: 10px; margin-bottom: 10px;">
			<center>
			<button class="remove-bottom" onclick="toggleMap();">Map</button>
			<button class="remove-bottom" onclick="toggleCar();">Car</button>
			<button class="remove-bottom" onclick="toggleHelp();">Help</button>
			</center>
			<hr style="margin-bottom: 10px;"/>
		</div>
		<div id="toggleMap" class="sixteen columns remove-bottom">
<?php		
	if(isset($_SESSION['latd'])&&isset($_SESSION['lngd'])){
		getNearDest(0.15);
		setDest("scripts/setDest.php");
		makeMap("dest");
		}
	else {
		getNearBy(0.15);
		setDest("scripts/setDest.php");
		makeMap("nodest");
		}
	clearDest("scripts/clearDest.php");
	clearRide("scripts/clearRide.php");
?>
		</div>
		<div id="toggleCar" style="display:none;" class="sixteen columns remove-bottom">
			<div class="five columns ">
<?php

	setLatestLeave("scripts/setLatestLeave.php");
?>
			</div>
			<div class="five columns ">
<?php
	if(0==strcmp($_SESSION['type'],"offer")){
		seats("scripts/seats.php","scripts/seatsDisplay.php");
		echo "<br>";
		myCar("scripts/myCar.php");
		}
?>
			</div>
			<div class="five columns ">
<?php
	myRide("scripts/myRide.php");
?>
			</div>
		</div>
		<div id="help" style="display:none;" class="sixteen columns remove-bottom">
		</div>
<?php
	}
else {
	echo "<hr style='remove-bottom' />";
	require 'scripts/login.php';
	echo "<br>";
	require 'scripts/register.php';
	}
// Ask for ride is in main.js
?>
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
</script>
</html>
