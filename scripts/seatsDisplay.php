<?php

session_start();
require 'phpfunctions.php';

getSeats();
if($_SESSION['numberavailableSeats']>1) echo "There are currently " . $_SESSION['numberavailableSeats'] . " seats avalable in your car.";

else if($_SESSION['numberavailableSeats']==1) echo "There is currently " . $_SESSION['numberavailableSeats'] . " seat avalable in your car.";

else if($_SESSION['numberavailableSeats']==0) echo "There are currently no seats avalable in your car.";

?>
