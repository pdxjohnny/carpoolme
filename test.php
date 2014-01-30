<?php
session_start();
require 'scripts/phpfunctions.php';
getNearBy();
showNearBy();
var_dump($_SESSION['nearby']);
?>
