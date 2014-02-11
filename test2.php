<span id="returnSpan" ></span>
<span id="inMyCarSpan" ></span>
<button onclick="kickFromCar('tommy');" >kick</button><br>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
function myCar(){
	$.ajax({
		type: "GET",
		url: "test/myCar.php",
		data: {},
		success: function(data){
			data = data.split('%');

			var incar = JSON.parse(data[0]);
			inMyCar(incar);

			var wantcar = JSON.parse(data[1]);
			wantMyCar(wantcar);
			//displaySeats();
			}
		});
	}

function inMyCar(incar){
	if(incar == null) $('#inMyCarSpan').html("There is no one in your car.<br>");
	else {
		if(incar.length == 1) $('#inMyCarSpan').html("There is one person in your car.<br>");
		else $('#inMyCarSpan').html("There are " + incar.length + " people in your car.<br>");
		for(var i = 0; i < incar.length; i++){
			$('#inMyCarSpan').append(incar[i][0]+'<button onclick="kickFromCar("'+incar[i][0]+'");" >kick</button><br>');
			}
		}
	}

function kickFromCar(tokickval){
	$('#returnSpan').show();
	$('#returnSpan').html("Kicking "+tokickval+"... <br>");
	$.ajax({
		type: "POST",
		url: "test/kick.php",		
		data: {
			tokick: tokickval
			},
		success: function(data){
			$('#returnSpan').show();
			$('#returnSpan').html(data+"<br>");
			$('#returnSpan').delay(9000).fadeOut();
			}
		})
	event.preventDefault();
	myCar();
	}
</script>
