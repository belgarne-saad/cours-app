<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cours_db";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed");
}
?>
