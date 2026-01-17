<?php
include("../auth/admin_gate.php");
include("../config/db.php");

$id = $_GET['id'];

$sql = "SELECT pdf FROM courses WHERE id = $id";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

unlink("../uploads/pdfs/" . $course['pdf']);

mysqli_query($conn, "DELETE FROM courses WHERE id = $id");

header("Location: dashboard.php");
?>
