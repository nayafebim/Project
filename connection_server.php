<?php
$servername = "localhost";
$username = "s642021135";
$password = "angkhanank.25";
$dbname = "db642021135";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>