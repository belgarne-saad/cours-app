<?php
include("../config/auth_gate.php");
include("../config/db.php");

// Prevent admin access
if ($_SESSION['user_type'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

$q = "";
if (isset($_GET['q']) && !empty(trim($_GET['q']))) {
    $q = mysqli_real_escape_string($conn, trim($_GET['q']));
    $sql = "SELECT * FROM courses WHERE title LIKE '%$q%' OR description LIKE '%$q%' ORDER BY id DESC";
} else {
    // If no search query, show all courses
    $sql = "SELECT * FROM courses ORDER BY id DESC";
}

$result = mysqli_query($conn, $sql);
if (!$result) {
  $db_error = mysqli_error($conn);
} else {
  $row_count = mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - Courses App</title>

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

  <!-- SEARCH AGAIN -->
  <div class="card mb-4 fade-in">
    <div class="card-body">
      <h5 class="section-title mb-3">Search a course</h5>

      <form action="search.php" method="GET" class="search-form">
        <input 
          type="text" 
          name="q" 
          class="form-control"
          placeholder="Type course name..."
          value="<?php echo htmlspecialchars($q); ?>"
          required
        >
        <button type="submit" class="btn btn-primary">Search</button>
      </form>
    </div>
  </div>

  <!-- RESULTS TITLE -->
  <?php if (!empty($q)): ?>
  <h4 class="section-title mb-4">
    Results for: 
    <span style="color: var(--accent-primary);">
      "<?php echo htmlspecialchars($q); ?>"
    </span>
  </h4>
  <?php else: ?>
  <h4 class="section-title mb-4">All Courses</h4>
  <?php endif; ?>

  <!-- DEBUG INFO -->
  <div class="mb-3">
    <?php
    if (isset($db_error)) {
        echo '<div class="text-danger">DB error: ' . htmlspecialchars($db_error) . '</div>';
    } else {
        echo '<div class="text-muted">Query: ' . htmlspecialchars($sql) . '</div>';
        echo '<div class="text-muted">Rows: ' . (isset($row_count) ? (int)$row_count : '0') . '</div>';
    }
    ?>
  </div>

  <!-- RESULTS -->
  <div class="row">

    <?php
    if (isset($row_count) && $row_count > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
    ?>

      <div class="col-md-4 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">
              <?php echo $row['title']; ?>
            </h5>

            <p class="card-text text-secondary">
              <?php echo substr($row['description'], 0, 100); ?>...
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
