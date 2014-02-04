<select id="select">
<option value="2014-02-12 19:09:00">2014-02-12 19:09:00</option>
<option value="2014-02-03 14:00:00">2014-02-03 14:00:00</option>
</select>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>

function userTime(time){

	var temp1 = time.split('-');
	var temp2 = temp1[2].split(' ');
	var temp3 = temp2[1].split(':');
	var out = [temp1[0], temp1[1], temp2[0], temp3[0], temp3[1], temp3[2]];
	return out;

	}

$(function () {
  $("#select").click(function() {
    var val = $(this).val();
    userTime(val);
  });
});
</script>
