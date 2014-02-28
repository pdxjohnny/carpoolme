<span id="returnSpan" style="display:none;" ></span>

<form id="profilePictureUpload" enctype="multipart/form-data">
	<input name="file" type="file" />
	<input type="submit" name="submit" value="Upload">
</form>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

$('#profilePictureUpload').submit(function(){
	var formData = new FormData($('#profilePictureUpload')[0]);
	if(formData == null) {
		$('#returnSpan').show();
		$('#returnSpan').html("Please select a file. <br>");
		$('#returnSpan').delay(9000).fadeOut();	
		return false;	
		}
	$.ajax({
		url: 'profiles/pictures.php',
		type: 'POST',
		xhr: function() { 
			var myXhr = $.ajaxSettings.xhr();
			return myXhr;
			},
		data: formData,
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			},
		cache: false,
		contentType: false,
		processData: false
		});
	return false;
	});

</script>
