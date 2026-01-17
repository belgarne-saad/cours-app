<?php
include("../config/auth_gate.php");
include("../config/db.php");

// Prevent admin access
if ($_SESSION['user_type'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM courses WHERE id = $id";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $course['title']; ?> - Courses App</title>

  <!-- Bootstrap (for grid system only) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom CSS - Dark Mode with Left Sidebar -->
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="app-container">
  <!-- LEFT SIDEBAR -->
  <aside class="sidebar">
    <!-- Sidebar Header (Logo) -->
    <div class="sidebar-header">
      <a href="index.php" class="sidebar-logo">📘 Courses App</a>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
      <a href="index.php" class="sidebar-link">
        <span>🏠</span>
        <span>Home</span>
      </a>
      <a href="../admin/dashboard.php" class="sidebar-link">
        <span>📤</span>
        <span>Upload</span>
      </a>
      <a href="#" class="sidebar-link">
        <span>🚪</span>
        <span>Logout</span>
      </a>
    </nav>
  </aside>
  
  <!-- MAIN CONTENT -->
  <main class="main-content">
    <div class="card fade-in">
      <div class="card-body">
        <h3 class="page-title"><?php echo $course['title']; ?></h3>
        <p class="card-text text-secondary mb-4"><?php echo $course['description']; ?></p>

        <a 
          href="../uploads/pdfs/<?php echo $course['pdf']; ?>" 
          download
          class="btn btn-success mt-3"
        >
          📥 Download PDF
        </a>
      </div>
    </div>
  </main>
</div>

<!-- Custom JavaScript - Simple Vanilla JS -->
<script src="../assets/js/app.js"></script>

</body>
</html>
