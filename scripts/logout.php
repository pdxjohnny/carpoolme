<?php
if(isset($_POST['logout'])){
	session_destroy();
	echo "<meta http-equiv='refresh' content='0'>";
	}
else{
	?>
	
	<html>
		<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="logout">
		<input type="submit" name="logout" value="Logout" />
		</form>
	</html>

<?php
	}
?>
