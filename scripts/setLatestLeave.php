<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

if(isset($_POST['setLatestLeave'])) {

	$whattime = $_SESSION['latestLeave'] = $_POST['datetime'];
	$whattime = mysql_real_escape_string($whattime);
	$whatname = $_SESSION['username'];
	if((!$whattime)) exit ("<meta http-equiv='refresh' content='0'>");
	
	updateString("latestleave",$whattime,$whatname);
	echo "<meta http-equiv='refresh' content='0'>";

	}
else {?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="lateleaveform">
<select name="hour" id="hour">
<script>
for(var i = 1;i<=24;i++){
	if(i<=12) document.write("<option value='"+i+"'>"+i+"</option>");
	else document.write("<option value='"+i+"'>"+(i-12)+"</option>");
	}
</script>
</select>
:
<select name="minute" id="minute">
<script>
for(var i = 0;i<=60;i++){
	if(i<10) i = '0'+i;
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
<div id="amorpm"></div>
on the
<select name="date" id="date">
<script>
var dateYMD = new Date();
var month = dateYMD.getMonth();
var maxdate = new Date(dateYMD.getFullYear(), month + 1, 0);
maxdate = maxdate.getDate();
var inputdate;
for(var i = 0;i<=14;i++){
	inputdate = dateYMD.getDate()+i;
	if(inputdate>maxdate) inputdate = inputdate-maxdate;
	document.write("<option value='"+inputdate+"'>"+inputdate+"</option>");
	}
$( document ).ready(function() {
	$( '#setLatestLeave' ).click(function() {
		if((dateYMD.getMonth()+1)<10) var month = '0'+(dateYMD.getMonth()+1);
		else var month = dateYMD.getMonth()+1;
		var predate = $( "#date" ).val();
		var prehour = $( "#hour" ).val();
		var minute = $( "#minute" ).val();
		if(predate<10) var date = '0'+predate;
		else var date = predate;
		if(prehour<10) var hour = '0'+prehour;
		else var hour = prehour;
		var ymd = dateYMD.getFullYear()+'-'+month+'-'+date+' '+hour+':'+minute+':00';
		$('#datetime').val(ymd);
		});
	 $("#select").change(function() {
		var val = $(this).val();
		if(val === "pilot") {
			$('#amorpm').html(ymd);
			$("#seatsdiv").show();
			});
		});
	});
</script>
</select>
<input value="" id="datetime" name="datetime" type="hidden">
<input value="Latest Leave Time" id="setLatestLeave" name="setLatestLeave" type="submit">
</form>
<?php
	}
?>
