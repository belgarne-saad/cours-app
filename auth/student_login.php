<?php
/**
 * STUDENT LOGIN PAGE
 * 
 * Allows existing students to login.
 * If no account exists, redirects to registration.
 * Passwords are hashed using password_hash() function.
 */
session_start();
include("../config/db.php");

$error = "";
$success = false;

// If already authenticated as student, redirect to home
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'student') {
    header("Location: ../public/index.php");
    exit();
}

// If admin logged in, redirect to dashboard
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    
    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password";
    } else {
        try {
            // Check if student exists
            $sql = "SELECT id, name, email, password FROM students WHERE email = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $student = $result->fetch_assoc();
                    
                    // Verify password
                    if (password_verify($password, $student['password'])) {
                        // Login successful - set session
                        $_SESSION['user_id'] = $student['id'];
                        $_SESSION['user_type'] = 'student';
                    $_SESSION['user_name'] = $student['name'];
                    $_SESSION['user_email'] = $student['email'];
                    $success = true;
                    
                    // Redirect to student home
                    header("Refresh: 1; url=../public/index.php");
                } else {
                    $error = "Email or password is incorrect!";
                }
            } else {
                $error = "No account found with this email. Please register first.";
            }
            $stmt->close();
            } else {
                $error = "Database error. Please try again.";
            }
        } catch (mysqli_sql_exception $e) {
            if (strpos($e->getMessage(), "doesn't exist") !== false) {
                $error = "⚠️ System not initialized! Database setup required.";
            } else {
                $error = "Database error. Please try again.";
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
    <title>Student Login - Courses App</title>
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
        .setup-required {
            background: #4a3f2b;
            border-left: 4px solid #ffd43b;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #ffe066;
        }
        .setup-required p {
            margin: 0 0 10px 0;
        }
        .setup-required .btn-setup {
            display: inline-block;
            background: #ffd43b;
            color: #1e1e2e;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-right: 10px;
        }
        .setup-required .btn-setup:hover {
            background: #ffdd52;
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
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>👨‍🎓 Student Login</h2>
            <p>Sign in to your account</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                ✅ Login successful! Redirecting...
            </div>
        <?php elseif (!empty($error)): ?>
            <?php if (strpos($error, "not initialized") !== false): ?>
                <div class="setup-required">
                    <p><strong>⚠️ System Setup Required</strong></p>
                    <p>The database hasn't been initialized yet. Please run the setup script first.</p>
                    <a href="setup.php" class="btn-setup">🗄️ Run Setup Now</a>
                    <a href="index.php" class="btn-setup" style="background: #495057; color: white;">← Back</a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    ❌ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <form method="POST" action="">
            <input 
                type="email" 
                name="email" 
                class="form-control" 
                placeholder="Email address" 
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
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
            Don't have an account? <a href="student_register.php">Register here</a>
            <br>
            <a href="index.php">← Back to choice</a>
        </div>
    </div>
</body>
</html>
