<?php
$upload_dir = "/var/www/carpool/profiles/pictures/";
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$maxsize = 2000000;
if ($_FILES["file"]["size"] < $maxsize)
  {
  if ($_FILES["file"]["error"] > 0)
    {
    if(($_FILES["file"]["error"])==4) echo "No file selected";
    else echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    if (file_exists($upload_dir . $_FILES["file"]["name"]))
      {
      move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $_FILES["file"]["name"]);
      echo "File replaced";
      }
    else
      {
      move_uploaded_file($_FILES["file"]["tmp_name"], $upload_dir . $_FILES["file"]["name"]);
      echo "File uploaded";
      }
    }
  }
else
  {
  echo "Invalid file";
  }
?>
