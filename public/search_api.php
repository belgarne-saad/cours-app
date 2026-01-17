<?php
include("../config/auth_gate.php");
include("../config/db.php");

// Prevent admin access
if ($_SESSION['user_type'] === 'admin') {
    http_response_code(403);
    die(json_encode(['error' => 'Access denied']));
}

header('Content-Type: application/json; charset=utf-8');

$q = "";
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $q = mysqli_real_escape_string($conn, trim($_GET['q']));
    $sql = "SELECT id, title, description FROM courses WHERE title LIKE '%$q%' OR description LIKE '%$q%' ORDER BY id DESC LIMIT 50";
} else {
    // If no query, return latest courses
    $sql = "SELECT id, title, description FROM courses ORDER BY id DESC LIMIT 10";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
    echo json_encode(['error' => mysqli_error($conn)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
