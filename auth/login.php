<?php
session_start();
include("../config/db.php");

$error = "";
$success = false;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        try {
            // Query to check admin credentials
            $sql = "SELECT * FROM admins WHERE username = ?";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                $error = "Database error. Please contact administrator.";
            } else {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $admin = $result->fetch_assoc();
                    // Verify password
                    if (password_verify($password, $admin['password'])) {
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_id'] = $admin['id'];
                        $_SESSION['admin_username'] = $admin['username'];
                        $success = true;
                        // Redirect after 1 second
                        header("Refresh: 1; url=../admin/dashboard.php");
                    } else {
                        $error = "Username or password is incorrect!";
                    }
                } else {
                    $error = "Username or password is incorrect!";
                }
                $stmt->close();
            }
        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                $error = "⚠️ System not initialized! Please run setup first.";
            } else {
                $error = "Database error: " . $e->getMessage();
            }
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
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            background: #2a2a3a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        .login-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
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
            background: #ff6b6b;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: none;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #5a7cfa;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>📘 Admin Panel</h2>
            <p class="text-secondary">Courses App</p>
        </div>

            <?php if (strpos($error, "not initialized") !== false): ?>
                <div style="text-align: center; margin-top: 15px; padding: 15px; background: #3a3a4a; border-radius: 5px; color: #ffd43b;">
                    <p style="margin: 0 0 10px 0;"><strong>First time setup required:</strong></p>
                    <a href="setup.php" style="display: inline-block; padding: 10px 20px; background: #5a7cfa; color: white; text-decoration: none; border-radius: 5px;">Click here to run setup</a>
                </div>
            <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert" style="background: #51cf66; color: white; text-align: center;">
                ✅ Login successful! Redirecting to dashboard...
            </div>
        <?php elseif (!empty($error)): ?>
            <div class="alert alert-danger" role="alert" style="background: #ff6b6b; color: white;">
                ❌ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

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

        <div class="back-link">
            <a href="../public/index.php">← Back to Home</a>
        </div>
    </div>
</body>
</html>
