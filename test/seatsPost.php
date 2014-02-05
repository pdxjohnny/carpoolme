<?php

session_start();
//if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

	$_SESSION['totalSeats'] = $_POST['seats'];
	if(!$_POST['seats']) exit ("<meta http-equiv='refresh' content='0'>");
	
	updateNum("spots",$$_POST['seats'],$_SESSION['username']);
	echo "<meta http-equiv='refresh' content='0'>";

else { ?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="seatsupdateform">
<?php if($_SESSION['seats']==NULL) echo "Seats Available: ";
else echo "Update Seats Available: "; ?>
<select name="seats" id="seats">
<script>
for(var i = 1;i<=10;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<?php if($_SESSION['seats']==NULL) echo '<input value="Set" id="seatsform" name="seatsform" type="submit"><br>';
else echo '<input value="Update" id="seatsform" name="seatsform" type="submit"><br>'; ?>
</div>
</form>

<?php
	}
?>
