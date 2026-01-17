<?php
include("../auth/admin_gate.php");
include("../config/db.php");

$sql = "SELECT * FROM courses ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Prof - Courses App</title>

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
      <a href="dashboard.php" class="sidebar-link active">
        <span>📊</span>
        <span>Dashboard</span>
      </a>
      <a href="add_course.php" class="sidebar-link">
        <span>➕</span>
        <span>Add Course</span>
      </a>
      <a href="../auth/logout.php" class="sidebar-link">
        <span>🚪</span>
        <span>Logout</span>
      </a>
    </nav>

    <!-- Admin Info -->
    <div style="padding: 15px; margin-top: 20px; background: #3a3a4a; border-radius: 5px; text-align: center; color: #aaa; font-size: 12px; border-top: 1px solid #4a4a5a;">
      <p style="margin: 0 0 8px 0;"><strong style="color: #ffd43b;">👨‍🏫 Admin</strong></p>
      <p style="margin: 0; font-size: 11px;">Administrator</p>
    </div>
  </aside>
  
  <!-- MAIN CONTENT -->
  <main class="main-content">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="page-title">Dashboard Prof</h3>
    <a href="add_course.php" class="btn btn-primary">+ Add Course</a>
  </div>

  <div class="row">

    <?php
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
    ?>

      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">
              <?php echo $row['title']; ?>
            </h5>

            <p class="card-text text-secondary">
              <?php echo $row['description']; ?>
            </p>
          </div>

          <div class="card-footer d-flex justify-content-between gap-2">
            <a href="edit_course.php?id=<?php echo $row['id']; ?>" 
               class="btn btn-warning flex-fill">
              Edit
            </a>

            <a href="delete_course.php?id=<?php echo $row['id']; ?>" 
               class="btn btn-danger flex-fill"
               onclick="return confirm('Delete this course ?')">
              Delete
            </a>
          </div>
        </div>
      </div>

    <?php
        }
    } else {
        echo "<p class='text-muted'>No courses found.</p>";
    }
    ?>

  </div>
  </main>
</div>

<!-- Custom JavaScript - Simple Vanilla JS -->
<script src="../assets/js/app.js"></script>

</body>
</html>
