<?php
#checking if form has been submitted
if (isset($_POST['cookie'])){
	setcookie("username",$_POST['username'],time()+3600);
	}

if (isset($_COOKIE['username'])){
	$_SESSION['username'] = $_COOKIE['username'];
	}
?>
<!-- HTML Page-->
<html>
<body> 
<form action= "<?php echo $_SERVER['PHP_SELF']; ?>" method ="POST">
Username : <br>
<input name="username" value="<?php echo $_SESSION['username']; ?>" />
<br>
Remember me 
<input type ="checkbox" name="cookie" value="false"></br>
<input type="submit" value="Login">
</form>
</body>
</html>
