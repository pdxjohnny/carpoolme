<?php
session_start();
require 'scripts/phpfunctions.php';
getNearBy();
echo "Users near you<br>";
showNearBy();
?>
