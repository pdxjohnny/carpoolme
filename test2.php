<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="scripts/main.js"></script>

<div id="returnSpan" ></div>
<br>
<div id="rDays" >
Sunday<input type="checkbox" value="0"><br>
Monday<input type="checkbox" value="1"><br>
Tuesday<input type="checkbox" value="2"><br>
Wednesday<input type="checkbox" value="3"><br>
Thursday<input type="checkbox" value="4"><br>
Friday<input type="checkbox" value="5"><br>
Saturday<input type="checkbox" value="6"><br>
</div>
<button onclick="getDays()" >Days</button></form><br>
<br>
<div id="thedays" ></div>
<div id="thenumber" ></div>


<script>

$('#time').change(function(){
	console.log($(this).val());
	});

function getDays(){
	var days = [];
	$('#rDays :checkbox:checked').each(function(i){
		days[i] = $(this).val();
		});
	console.log(days);
	$('#thenumber').html(toNumDays(days));
	$('#thedays').html(toDays(toNumDays(days)));
	}

function toDays(num){
	var days = [];
	if(num[0] == 1) days.push(" Sundays");
	if(num[1] == 1) days.push(" Mondays");
	if(num[2] == 1) days.push(" Tuesdays");
	if(num[3] == 1) days.push(" Wednesdays");
	if(num[4] == 1) days.push(" Thursdays");
	if(num[5] == 1) days.push(" Fridays");
	if(num[6] == 1) days.push(" Saturdays");
	if (days.length > 1){
		days[days.length-1] = " and" + days[days.length-1] + ". ";
		}
	return days;
	}

function toNumDays(days){
	var numDays = [ 0, 0, 0, 0, 0, 0, 0 ];
	for( var i = 0; i < days.length ; i++ ){
		if(days[i] === "0") numDays[0] = 1;
		if(days[i] === '1') numDays[1] = 1;
		if(days[i] === '2') numDays[2] = 1;
		if(days[i] === '3') numDays[3] = 1;
		if(days[i] === '4') numDays[4] = 1;
		if(days[i] === '5') numDays[5] = 1;
		if(days[i] === '6') numDays[6] = 1;
		}
	numDays = numDays.toString().replace(/,/g, '');
	console.log(numDays);
	return numDays;
	}


</script>
