<?php
$name = "test6";
if(file_put_contents("profiles/users", $name . "\n", FILE_APPEND)) echo "Success, added $name to profiles/users";
else echo "Error";

?>
