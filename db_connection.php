<?php
$servername = "localhost";
$username = "root";
$password = "01119275065";
$dbname = "mqtt_connect";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error)
 {
    die("Connection failed: " . $connection->connect_error);
 }
?>
