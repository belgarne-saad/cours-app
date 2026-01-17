<?php
/**
 * DATABASE INITIALIZATION
 * 
 * Creates the students table if it doesn't exist.
 * Run this script ONCE to initialize the database.
 */

include("../config/db.php");

$messages = [];

// Create students table
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX email_idx (email)
)";

if (mysqli_query($conn, $sql)) {
    $messages[] = ["type" => "success", "text" => "✅ Students table created successfully"];
} else {
    $messages[] = ["type" => "error", "text" => "❌ Error: " . mysqli_error($conn)];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Courses App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e1e2e 0%, #2d2d44 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .setup-container {
            background: #2a2a3a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        .setup-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            border-left: 4px solid;
        }
        .message.success {
            background: #2d4a2b;
            border-left-color: #51cf66;
            color: #51cf66;
        }
        .message.error {
            background: #4a2b2b;
            border-left-color: #ff6b6b;
            color: #ff8c8c;
        }
        .info-box {
            background: #3a3a4a;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            color: #aaa;
            border-left: 4px solid #5a7cfa;
        }
        .info-box h4 {
            color: #5a7cfa;
            margin-bottom: 10px;
        }
        .info-box code {
            background: #2a2a3a;
            padding: 2px 6px;
            border-radius: 3px;
            color: #90c7ff;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-primary {
            background: #5a7cfa;
            color: white;
        }
        .btn-primary:hover {
            background: #4c6fd9;
        }
        .btn-secondary {
            background: #495057;
            color: white;
        }
        .btn-secondary:hover {
            background: #3a4349;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-header">
            <h2>🗄️ Database Setup</h2>
            <p>Courses App - Initialize Students Table</p>
        </div>

        <?php foreach ($messages as $msg): ?>
            <div class="message <?php echo $msg['type']; ?>">
                <?php echo $msg['text']; ?>
            </div>
        <?php endforeach; ?>

        <div class="info-box">
            <h4>📋 Students Table Schema</h4>
            <p><strong>Columns:</strong></p>
            <ul>
                <li><code>id</code> - Auto-increment primary key</li>
                <li><code>name</code> - Student full name</li>
                <li><code>email</code> - Unique email address</li>
                <li><code>password</code> - Hashed password (NOT stored as plain text)</li>
                <li><code>created_at</code> - Account creation timestamp</li>
                <li><code>updated_at</code> - Last update timestamp</li>
            </ul>
        </div>

        <div class="info-box">
            <h4>🔐 Security Notes</h4>
            <ul>
                <li>Passwords are hashed using PHP's <code>password_hash()</code> function</li>
                <li>Email is unique to prevent duplicate accounts</li>
                <li>All student data is stored in this table</li>
                <li>Admin credentials are hardcoded (NOT in database)</li>
            </ul>
        </div>

        <div class="button-group">
            <a href="index.php" class="btn btn-primary">Go to Login</a>
            <a href="../../public/index.php" class="btn btn-secondary">Go to Application</a>
        </div>
    </div>
</body>
</html>

