<?php
// Binary Large Object (BLOB) datatype is used to store images directly to database table. 

$conn = mysqli_connect('localhost', 'root', '') or die("Unable to connect to database.");
if($conn){
  $dbquery = "CREATE DATABASE IF NOT EXISTS `db_test`";
  mysqli_query($conn, $dbquery);

  mysqli_select_db($conn, 'db_test');

  $tablequery = "CREATE TABLE IF NOT EXISTS `files` (id INT AUTO_INCREMENT PRIMARY KEY, file_data MEDIUMBLOB NOT NULL)";
  mysqli_query($conn, $tablequery);

  // Insert File to Database
  if(isset($_POST['upload'])){
    $MY_FILE = $_FILES['file']['tmp_name'];
    $file = fopen($MY_FILE, 'r');
    $file_contents = fread($file, filesize($MY_FILE));
    fclose($file);
    $file_contents = addslashes($file_contents);
    $query = "INSERT INTO `files` SET file_data='$file_contents'";
    $result = mysqli_query($conn, $query);
    if($result){
      echo "File inserted into files table successfully.<br>";
    }
  }

  //Read file from database
  $query2 = "SELECT * from `files`";
  $result2 = mysqli_query($conn, $query2);
  $images = array();
  while ($row = mysqli_fetch_assoc($result2)) {
    $images[] = $row['file_data'];
  }

  foreach ($images as $image) {
    echo '<img width="500" src="data:image/jpeg;base64,'. base64_encode($image) .'" />';
  }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Image to Database</title>
</head>
<body>
  <form name="file" method="post" enctype="multipart/form-data">
    <input type="file" name="file" value="" />
    <input type="submit" name="upload" value="Upload">
  </form>
</body>
</html>

