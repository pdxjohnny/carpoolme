<?php

	$table="carpool_members"; // Table name

	// Create connection
	$con=mysqli_connect("***REMOVED***","***REMOVED***","***REMOVED***","***REMOVED***");

	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

	$result = mysqli_query($con,"SELECT * FROM $table;");

echo "<table border='1'>
<tr>
<th>ID</th>
<th>Username</th>
<th>Password</th>
<th>Email</th>
<th>Latidute</th>
<th>Logitude</th>
<th>Type</th>
<th>DLatidute</th>
<th>DLogitude</th>
</tr>";

while($row = mysqli_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['id'] . "</td>";
  echo "<td>" . $row['username'] . "</td>";
  echo "<td>" . $row['password'] . "</td>";
  echo "<td>" . $row['email'] . "</td>";
  echo "<td>" . $row['latitude'] . "</td>";
  echo "<td>" . $row['longitude'] . "</td>";
  echo "<td>" . $row['type'] . "</td>";
  echo "<td>" . $row['dlatitude'] . "</td>";
  echo "<td>" . $row['dlongitude'] . "</td>";
  echo "</tr>";
  }
echo "</table>";

	mysqli_close($con);

?>
