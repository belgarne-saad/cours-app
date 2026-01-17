<?php
include("../auth/admin_gate.php");
include("../config/db.php");

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM courses WHERE id=$id");
$course = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    if (!empty($_FILES['pdf']['name'])) {
        unlink("../uploads/pdfs/" . $course['pdf']);

        $new_pdf = $_FILES['pdf']['name'];
        $tmp = $_FILES['pdf']['tmp_name'];
        move_uploaded_file($tmp, "../uploads/pdfs/" . $new_pdf);

        mysqli_query($conn, "UPDATE courses 
                             SET title='$title', description='$description', pdf='$new_pdf'
                             WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE courses 
                             SET title='$title', description='$description'
                             WHERE id=$id");
    }

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Course - Courses App</title>

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
        <h4 class="page-title mb-4">Edit Course</h4>

        <form method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control"
                   value="<?php echo $course['title']; ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="5"><?php echo $course['description']; ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Change PDF (optional)</label>
            <input type="file" name="pdf" class="form-control" accept=".pdf">
          </div>

          <button name="update" type="submit" class="btn btn-warning">Update Course</button>
        </form>
      </div>
    </div>
  </main>
</div>

<!-- Custom JavaScript - Simple Vanilla JS -->
<script src="../assets/js/app.js"></script>

</body>
</html>
