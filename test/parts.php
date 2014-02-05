<?php

if(!defined('INCLUDE_CHECK')) die("<script type='text/javascript'>history.go(-1);</script>");

function includes(){?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="scripts/main.js"></script>
<?php
	}

function test(){?>
<form id="form">
<input id="address" type="textbox" placeholder="Destination">
<input id="other" type="textbox" placeholder="other">
<input value="Submit" type="submit"><br>
</form>

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
			$('#returnSpan').html(data);
			}
		});
	event.preventDefault();
	});
});
</script>
<?php
	}

function logout($postto){?>

<form method="post" id="logoutform">
<input type="submit" name="logout" id="logout" value="Logout" />
</form>
<script>
$( document ).ready(function() {
	$( "#logoutform" ).submit(function( event ) {
	$.ajax({
		url: "<?php echo $postto; ?>",
		success: function(data){
			$('#returnSpan').html(data);
			}
		});
	event.preventDefault();
	});
});
</script>

<?php
	}

function setLatestLeave($postto){ ?>
<form id="lateleaveform">
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
<span id="amorpm"></span>
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
</script>
</select>
<span id="datesufix"></span>
<input value="" id="datetime" name="datetime" type="hidden">
<input value="Latest Leave Time" id="setLatestLeave" name="setLatestLeave" type="submit">
</form>
<script>
function dateSufix(date){
	if(date == 1) {
		return "st";
		}
	else if(date == 2){
		return "nd";
		}
	else if(date == 3){
		return "rd";
		}
	else {
		return "th";
		}
	}

$( document ).ready(function() {
	$('#amorpm').html(" am");

	var val = $("#date").val();
	if(val.length == 1) var sufix = dateSufix(val);
	else if (val[0] == 1) var sufix = dateSufix(val);
	else var sufix = dateSufix(val[1]);
	$('#datesufix').html(sufix);

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
	 $("#hour").click(function() {
		var val = $(this).val();
		if(val <= 12) {
			$('#amorpm').html(" am");
			}
		else {
			$('#amorpm').html(" pm");
			}
		});
	 $("#date").click(function() {
		var val = $(this).val();
		var sufix;
		if(val.length == 1) sufix = dateSufix(val);
		else if (val[0] == 1) sufix = dateSufix(val);
		else sufix = dateSufix(val[1]);
		$('#datesufix').html(sufix);
		});

	$( "#lateleaveform" ).submit(function( event ) {
		var datetimeval = $('#datetime').val();
		$.ajax({
			type: "POST",
			url: "<?php echo $postto; ?>",
			data: {datetime: datetimeval, username: "<?php echo $_SESSION['username']; ?>"},
			success: function(data){
				$('#returnSpan').html(data);
				}
			});
		event.preventDefault();
		});
	});
</script>
<?php
	}

function setDest($postto){ ?>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" name="destform">
<input name="GPSlatd" id="GPSlatd" type="hidden" value="">
<input name="GPSlongd" id="GPSlongd" type="hidden" value="">
    <div id="panel">
      <input id="address" type="textbox" placeholder="Destination">
      <input type="button" value="Find" onclick="codeAddress('images/mydest.png')">
    </div>
<div id="mapholder"></div>
</form>
<script>
function dateSufix(date){
	if(date == 1) {
		return "st";
		}
	else if(date == 2){
		return "nd";
		}
	else if(date == 3){
		return "rd";
		}
	else {
		return "th";
		}
	}

$( document ).ready(function() {
	$('#amorpm').html(" am");

	var val = $("#date").val();
	if(val.length == 1) var sufix = dateSufix(val);
	else if (val[0] == 1) var sufix = dateSufix(val);
	else var sufix = dateSufix(val[1]);
	$('#datesufix').html(sufix);

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
	 $("#hour").click(function() {
		var val = $(this).val();
		if(val <= 12) {
			$('#amorpm').html(" am");
			}
		else {
			$('#amorpm').html(" pm");
			}
		});
	 $("#date").click(function() {
		var val = $(this).val();
		var sufix;
		if(val.length == 1) sufix = dateSufix(val);
		else if (val[0] == 1) sufix = dateSufix(val);
		else sufix = dateSufix(val[1]);
		$('#datesufix').html(sufix);
		});

	$( "#lateleaveform" ).submit(function( event ) {
		var datetimeval = $('#datetime').val();
		$.ajax({
			type: "POST",
			url: "<?php echo $postto; ?>",
			data: {datetime: datetimeval, username: "<?php echo $_SESSION['username']; ?>"},
			success: function(data){
				$('#returnSpan').html(data);
				}
			});
		event.preventDefault();
		});
	});
</script>
<?php
	}

?>
