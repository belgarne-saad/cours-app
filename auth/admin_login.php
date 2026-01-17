<?php
/**
 * ADMIN LOGIN PAGE - PROFESSEUR
 * 
 * Admin login with HARDCODED credentials (NOT in database).
 * 
 * CREDENTIALS:
 * - Username: admin
 * - Password: admin123
 * 
 * HOW IT WORKS:
 * 1. Check if submitted username and password match hardcoded values
 * 2. If correct: Set admin session
 * 3. If incorrect: Show error
 * 4. NO registration for admin - only hardcoded account
 */
session_start();

$error = "";
$success = false;

// If already authenticated, redirect
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../public/index.php");
    }
    exit();
}

// Hardcoded admin credentials (CHANGE THESE FOR PRODUCTION)
const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'admin123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        // Check credentials against hardcoded values
        if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
            // Login successful - set admin session
            $_SESSION['user_id'] = 'admin'; // Special ID for admin
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_name'] = 'Administrator';
            $_SESSION['username'] = ADMIN_USERNAME;
            $success = true;
            
            // Redirect to admin dashboard
            header("Refresh: 1; url=../admin/dashboard.php");
        } else {
            $error = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Courses App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e1e2e 0%, #2d2d44 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            background: #2a2a3a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        .login-header h2 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .login-header p {
            color: #aaa;
            margin: 0;
        }
        .form-control {
            background: #3a3a4a;
            border: 1px solid #4a4a5a;
            color: white;
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .form-control:focus {
            background: #3a3a4a;
            border-color: #5a7cfa;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(90, 124, 250, 0.25);
        }
        .form-control::placeholder {
            color: #999;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #5a7cfa;
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-login:hover {
            background: #4c6fd9;
        }
        .alert {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: none;
        }
        .alert-success {
            background: #51cf66;
            color: white;
        }
        .alert-danger {
            background: #ff6b6b;
            color: white;
        }
        .alert-info {
            background: #4a90e2;
            color: white;
            font-size: 13px;
        }
        .link-group {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }
        .link-group a {
            color: #5a7cfa;
            text-decoration: none;
            margin: 0 5px;
        }
        .link-group a:hover {
            text-decoration: underline;
        }
        .warning-box {
            background: #4a3f2b;
            border-left: 4px solid #ffd43b;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            color: #ffe066;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>👨‍🏫 Admin Login</h2>
            <p>Professeur Access</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                ✅ Login successful! Redirecting to dashboard...
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="warning-box">
            ⚠️ Admin credentials are hardcoded for security.
            For production, use a secure key management system.
        </div>

        <form method="POST" action="">
            <input 
                type="text" 
                name="username" 
                class="form-control" 
                placeholder="Username" 
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                required
            >
            <input 
                type="password" 
                name="password" 
                class="form-control" 
                placeholder="Password" 
                required
            >
            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="link-group">
            <a href="index.php">← Back to choice</a>
        </div>
    </div>
</body>
</html>
