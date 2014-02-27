
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
var jsSnearby = [];
nearby(function(){
	console.log(jsSnearby);
	});
function nearby(callback){
	document.write("starting<br>");
	$.ajax({
		type: "GET",
		url: "test"+"/nearby.php",
		data: {},
		success: function(data){
			jsSnearby = JSON.parse(data);
			callback();
			}
		});
	}
</script>
