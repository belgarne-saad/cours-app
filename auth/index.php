<?php
/**
 * MAIN AUTHENTICATION ENTRY POINT
 * 
 * This is the FIRST page users see when accessing the application.
 * Shows two choices: Étudiant (Student) or Professeur (Admin)
 * 
 * This page is accessible WITHOUT authentication.
 */
session_start();

// If already authenticated, redirect to appropriate dashboard
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] === 'admin') {
        header("Location: ../admin/dashboard.php");
        exit();
    } else {
        header("Location: ../public/index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - Courses App</title>
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
        .auth-container {
            background: #2a2a3a;
            padding: 60px 40px;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }
        .auth-header {
            text-align: center;
            margin-bottom: 50px;
            color: white;
        }
        .auth-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .auth-header p {
            color: #aaa;
            font-size: 16px;
        }
        .choices {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .choice-card {
            background: #3a3a4a;
            padding: 40px 20px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            text-decoration: none;
            color: white;
        }
        .choice-card:hover {
            background: #4a4a5a;
            border-color: #5a7cfa;
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(90, 124, 250, 0.2);
        }
        .choice-card a {
            text-decoration: none;
            color: inherit;
            display: block;
        }
        .choice-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }
        .choice-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .choice-desc {
            font-size: 14px;
            color: #aaa;
        }
        .choice-card:hover .choice-title {
            color: #5a7cfa;
        }
        .divider {
            text-align: center;
            color: #666;
            margin: 30px 0;
            font-size: 14px;
        }
        @media (max-width: 600px) {
            .choices {
                grid-template-columns: 1fr;
            }
            .auth-header h1 {
                font-size: 28px;
            }
            .auth-container {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>📘 Courses App</h1>
            <p>Educational Platform - Access Control</p>
        </div>

        <div class="choices">
            <div class="choice-card">
                <a href="student_login.php">
                    <div class="choice-icon">👨‍🎓</div>
                    <div class="choice-title">Étudiant</div>
                    <div class="choice-desc">Student Account</div>
                </a>
            </div>

            <div class="choice-card">
                <a href="admin_login.php">
                    <div class="choice-icon">👨‍🏫</div>
                    <div class="choice-title">Professeur</div>
                    <div class="choice-desc">Admin Account</div>
                </a>
            </div>
        </div>

        <div class="divider">
            <p>🔒 Secure Login System - All pages require authentication</p>
        </div>
    </div>
</body>
</html>
