<?php
/**
 * STUDENT REGISTRATION PAGE
 * 
 * Allows new students to create accounts.
 * Stores: name, email, hashed password.
 * Email must be unique.
 */
session_start();
include("../config/db.php");

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $password_confirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';
    
    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = "Please fill in all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters";
    } elseif ($password !== $password_confirm) {
        $error = "Passwords do not match";
    } else {
        try {
            // Check if email already exists
            $check_sql = "SELECT id FROM students WHERE email = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $result = $check_stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "Email already registered. Please login or use another email.";
            } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new student
            $insert_sql = "INSERT INTO students (name, email, password) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            
            if ($insert_stmt) {
                $insert_stmt->bind_param("sss", $name, $email, $hashed_password);
                
                if ($insert_stmt->execute()) {
                    $student_id = $insert_stmt->insert_id;
                    
                    // Set session - auto-login after registration
                    $_SESSION['user_id'] = $student_id;
                    $_SESSION['user_type'] = 'student';
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $success = true;
                    
                    // Redirect to student home
                    header("Refresh: 1; url=../public/index.php");
                } else {
                    $error = "Registration failed. Please try again.";
                }
                $insert_stmt->close();
            } else {
                $error = "Database error. Please try again.";
            }
        }
        $check_stmt->close();
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
    <title>Student Registration - Courses App</title>
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
        .register-container {
            background: #2a2a3a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            width: 100%;
        }
        .register-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        .register-header h2 {
            font-size: 28px;
            margin-bottom: 5px;
        }
        .register-header p {
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
        .btn-register {
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
        .btn-register:hover {
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
        .password-requirements {
            background: #3a3a4a;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #aaa;
        }
        .password-requirements ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .password-requirements li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>👨‍🎓 Student Registration</h2>
            <p>Create your account</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                ✅ Account created! Auto-login in progress...
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
                type="text" 
                name="name" 
                class="form-control" 
                placeholder="Full name" 
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                required
            >
            <input 
                type="email" 
                name="email" 
                class="form-control" 
                placeholder="Email address" 
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                required
            >

            <div class="password-requirements">
                <strong>Password Requirements:</strong>
                <ul>
                    <li>Minimum 6 characters</li>
                    <li>Use uppercase and numbers for better security</li>
                </ul>
            </div>

            <input 
                type="password" 
                name="password" 
                class="form-control" 
                placeholder="Password" 
                required
            >
            <input 
                type="password" 
                name="password_confirm" 
                class="form-control" 
                placeholder="Confirm password" 
                required
            >
            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="link-group">
            Already have an account? <a href="student_login.php">Login here</a>
            <br>
            <a href="index.php">← Back to choice</a>
        </div>
    </div>
</body>
</html>
