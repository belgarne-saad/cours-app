<?php
include("../auth/admin_gate.php");
include("../config/db.php");

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $pdf_name = $_FILES['pdf']['name'];
    $pdf_tmp = $_FILES['pdf']['tmp_name'];

    move_uploaded_file($pdf_tmp, "../uploads/pdfs/" . $pdf_name);

    mysqli_query($conn, "INSERT INTO courses (title, description, pdf)
                          VALUES ('$title', '$description', '$pdf_name')");

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Course - Courses App</title>

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
      <a href="../public/index.php" class="sidebar-logo">📘 Courses App</a>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
      <a href="../public/index.php" class="sidebar-link">
        <span>🏠</span>
        <span>Home</span>
      </a>
      <a href="dashboard.php" class="sidebar-link active">
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
        <h4 class="page-title mb-4">Add New Course</h4>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">PDF File</label>
            <input type="file" name="pdf" class="form-control" accept=".pdf" required>
          </div>

          <button name="submit" type="submit" class="btn btn-primary">Add Course</button>
        </form>
      </div>
    </div>
  </main>
</div>

<!-- Custom JavaScript - Simple Vanilla JS -->
<script src="../assets/js/app.js"></script>

</body>
</html>
