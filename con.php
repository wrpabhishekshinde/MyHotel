<?php
$con = mysqli_init();
mysqli_ssl_set($con,NULL,NULL, "certificate.pem", NULL, NULL);
mysqli_real_connect($con, "frtdbserver74.mysql.database.azure.com", "gsshinde", "Pass@123", "fin_demo", 3306, MYSQLI_CLIENT_SSL);
//print_r($con);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
  }
  //echo "Connected successfully";
?>
