<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'db.php';

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'ngo') {
    // Check if this NGO is verified
    $ngo_id = $_SESSION['user_id'];
    $check = $conn->prepare("SELECT verification_status FROM ngo_verifications WHERE ngo_id = ?");
    $check->bind_param("i", $ngo_id);
    $check->execute();
    $check->bind_result($status);
    $check->fetch();
    $check->close();

    if ($status !== 'Approved') {
        echo "<script>
            alert('Your account is not verified yet. Please wait for admin approval.');
            window.location.href = 'ngo_dashboard.php';
        </script>";
        exit();
    }
}

// Fetch all donations with donor name
$sql = "SELECT d.*, u.name AS donor_name FROM donations d JOIN users u ON d.user_id = u.id ORDER BY d.pickup_date DESC, d.pickup_time DESC";
$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Donations - Meal4All</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
<header>
  <h1>Meal4All</h1>
  <nav>
    <?php if (isset($_SESSION['user_id'])): ?>
      <?php if ($_SESSION['user_type'] === 'donor'): ?>
        <a href="donor_dashboard.php">Back to Dashboard</a>
      <?php elseif ($_SESSION['user_type'] === 'ngo'): ?>
        <a href="ngo_dashboard.php">Back to Dashboard</a>
      <?php elseif ($_SESSION['user_type'] === 'admin'): ?>
        <a href="admin_dashboard.php">Back to Dashboard</a>
      <?php endif; ?>
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.html">Login</a>
      <a href="register.html">Register</a>
    <?php endif; ?>
  </nav>
</header>

<div class="container">
  <h2 class="text-center mb-4">All Donations</h2>
  <div class="table-responsive shadow-sm rounded">
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-primary text-center">
        <tr>
          <th>Donor</th>
          <th>Food Description</th>
          <th>Quantity</th>
          <th>Date</th>
          <th>Time</th>
          <th>Location</th>
          <th>Additional Info</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['donor_name']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['food_description'])) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['quantity']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['pickup_date']) ?></td>
              <td class="text-center"><?= htmlspecialchars($row['pickup_time']) ?></td>
              <td><?= htmlspecialchars($row['location']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['additional_info'] ?? '')) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="7" class="text-center text-muted">No donations found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <h3 class="mt-5">Testimonials</h3>
  <blockquote class="blockquote">
    <p>"We received food on time and it helped us feed many in need." - Smile NGO</p>
  </blockquote>
</div>

<footer class="text-center mt-5 mb-3 text-muted">
  <p>&copy; 2025 Meal4All. Making a difference, one meal at a time.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
