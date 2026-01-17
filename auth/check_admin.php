<?php
include("../config/db.php");

echo "<h1>Admin Account Check</h1>";

// Check if admins table exists
$check_table = "SHOW TABLES LIKE 'admins'";
$result = mysqli_query($conn, $check_table);

if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red;'><strong>❌ Table 'admins' does not exist! Please run setup first.</strong></p>";
} else {
    echo "<p style='color: green;'><strong>✅ Table 'admins' exists</strong></p>";
    
    // Check if admin account exists
    $check_admin = "SELECT * FROM admins WHERE username = 'admin'";
    $admin_result = mysqli_query($conn, $check_admin);
    
    if (mysqli_num_rows($admin_result) == 0) {
        echo "<p style='color: red;'><strong>❌ No admin account found! Please run setup.</strong></p>";
    } else {
        echo "<p style='color: green;'><strong>✅ Admin account exists</strong></p>";
        $admin = mysqli_fetch_assoc($admin_result);
        echo "<p>Username: <strong>admin</strong></p>";
        echo "<p>Password: <strong>admin123</strong></p>";
        echo "<p>Hashed password stored: " . substr($admin['password'], 0, 20) . "...</p>";
    }
}

// Test password verification
echo "<hr>";
echo "<h3>Password Test:</h3>";
$test_pass = "admin123";
$test_hash = '$2y$10$abc'; // dummy
if (isset($admin) && isset($admin['password'])) {
    $verify = password_verify($test_pass, $admin['password']);
    echo "password_verify('admin123', stored_hash) = " . ($verify ? "TRUE ✅" : "FALSE ❌");
}
?>
