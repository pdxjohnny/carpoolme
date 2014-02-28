<!--
Application: Carpoolme.net
File: Change Log
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


<script src="/scripts/main.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

</head>
<body>

	<!-- Primary Page Layout
	================================================== -->

	<!-- Delete everything in this .container and get started on your own site! -->

	<div class="container">
		<div class="sixteen columns remove-bottom">
			<h1 class="remove-bottom" style="margin-top: 40px">Carpoolme</h1>
			<h5 class="remove-bottom" >Beta v1.1 <a href="#" data-mailto="johnandersenpdx@gmail.com">Report Bug</a></h5>
<?php

session_start();
define('INCLUDE_CHECK',true);
$dir = "scripts";
require $dir . '/parts.php';
require $dir . '/phpfunctions.php';

if(isset($_SESSION['username'])){
	echo "User " . $_SESSION['username'] . " is logged in.";
	logout($dir . "/logout.php");
?>
			<br><center><span style="color: #4593C4; margin-top:10px;" id='returnSpan'></span></center>
			<hr/><br>
<?php
	}
// Ask for ride is in main.js
?>	
			<hr/><br>
		<div id="ChangeLog" class="sixteen columns remove-bottom">
		</div>
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

$('#ChangeLog').html(readFile("ChangeLog.txt").replace(/\n/g, "<br>"));
</script>
</html>
