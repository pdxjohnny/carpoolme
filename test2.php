<script type='text/javascript' src='http://code.jquery.com/jquery-1.6.2.js'></script>
<form id="form">
<input id="address" type="textbox" placeholder="Destination">
<input id="other" type="textbox" placeholder="other">
<input value="Submit" type="submit"><br>
</form>
<div id="resultDiv" ></div>

<script>
$( document ).ready(function() {
	$( "#form" ).submit(function( event ) {
	var add = $('#address').val();
	var other = $('#other').val();
	$.ajax({
		type: "POST",
		url: "test3.php",
		data: {test1: add, test2: other},
		success: function(data){
			$('#resultDiv').html(data);
			}
		});
	event.preventDefault();
	});
});
</script>
