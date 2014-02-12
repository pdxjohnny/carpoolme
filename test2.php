<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>keypress demo</title>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>

    <label for="target">Type Something:</label>
    <input id="target" type="text">
<br>
    <span id="output"></span>
<script>
$( "#target" ).keyup(function( event ) {
	$( "#output" ).html($(this).val());
	});
</script>
 
</body>
</html>
