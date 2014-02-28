<form enctype="multipart/form-data">
    <input name="file" type="file" />
    <input type="button" value="Upload" />
</form>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

$(':file').change(function(){
    var file = this.files[0];
    var name = file.name;
    var size = file.size;
    var type = file.type;
    //Your validation
});


$(':button').click(function(){
    var formData = new FormData($('form')[0]);
    $.ajax({
        url: 'test4.php',  //Server script to process data
        type: 'POST',
        xhr: function() {  // Custom XMLHttpRequest
            var myXhr = $.ajaxSettings.xhr();
            if(myXhr.upload){ // Check if upload property exists
            }
            return myXhr;
        },
        //Ajax events
	data: formData,
	success: function(data){
		console.log(data);
		},
        //Options to tell jQuery not to process data or worry about content-type.
        cache: false,
        contentType: false,
        processData: false
    });
});

</script>
