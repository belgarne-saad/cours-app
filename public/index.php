<?php
include("../config/auth_gate.php");
include("../config/db.php");

// Prevent admin access to student pages
if ($_SESSION['user_type'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$sql = "SELECT * FROM courses ORDER BY id DESC LIMIT 6";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Courses App - Educational Platform</title>

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
      <a href="index.php" class="sidebar-link active">
        <span>🏠</span>
        <span>Home</span>
      </a>
      <a href="../auth/logout.php" class="sidebar-link">
        <span>🚪</span>
        <span>Logout</span>
      </a>
    </nav>

    <!-- Student Info -->
    <div style="padding: 15px; margin-top: 20px; background: #3a3a4a; border-radius: 5px; text-align: center; color: #aaa; font-size: 12px; border-top: 1px solid #4a4a5a;">
      <p style="margin: 0 0 8px 0;"><strong style="color: #90c7ff;">👤 Student</strong></p>
      <p style="margin: 0; font-size: 11px; word-break: break-word;"><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
    </div>
  </aside>
  
  <!-- MAIN CONTENT -->
  <main class="main-content">

  <!-- SEARCH -->
  <div class="card mb-5 fade-in">
    <div class="card-body">
      <h4 class="section-title mb-3">Search a course</h4>

      <form action="search.php" method="GET" class="search-form" onsubmit="return false;">
        <input 
          type="text" 
          id="search-input"
          name="q" 
          class="form-control" 
          placeholder="Type course name..." 
          autocomplete="off"
        >
        <button type="submit" class="btn btn-primary">Search</button>
      </form>

      <div id="live-results" class="row mt-3"></div>
    </div>
  </div>

  <!-- LAST COURSES -->
  <h4 class="section-title mb-4">Latest courses</h4>

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
              <?php echo substr($row['description'], 0, 80); ?>...
            </p>
          </div>

          <div class="card-footer">
            <a 
              href="course.php?id=<?php echo $row['id']; ?>" 
              class="btn btn-outline w-100"
            >
              View course
            </a>
          </div>
        </div>
      </div>

    <?php
        }
    } else {
        echo "<p class='text-muted'>No courses available.</p>";
    }
    ?>

  </div>
  </main>
</div>

<!-- Custom JavaScript - Simple Vanilla JS -->
<script src="../assets/js/app.js"></script>

</body>
</html>
