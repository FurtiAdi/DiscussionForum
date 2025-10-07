<?php
  $host = 'mysql.dsv.su.se';
  $user = 'xxxxxx';
  $pass = 'xxxxxx';
  $dbname = 'xxxxx'; // name of my database

  
  // Connect to the MySQL
  $conn = new mysqli($host, $user, $pass, $dbname);
  
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>


