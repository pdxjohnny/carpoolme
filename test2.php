<select id="select">
<option value="client">Client Hours</option>
<option value="pilot">Pilot Hours</option>
</select>

<div id="seatsdiv" style="display:none;">
Seats:
<select name="seats" id="minute">
<script>
for(var i = 0;i<=10;i++){
	document.write("<option value='"+i+"'>"+i+"</option>");
	}
</script>
</select>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
$(function () {
  $("#select").change(function() {
    var val = $(this).val();
    if(val === "pilot") {
        $("#seatsdiv").show();
    }
    else {
        $("#seatsdiv").hide();
    }
  });
});
</script>
