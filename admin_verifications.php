<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html?error=unauthorized");
    exit();
}

require 'db.php';

// Handle approve/reject POST request
if (isset($_POST['action'], $_POST['verification_id'])) {
    $action = $_POST['action'];
    $verification_id = (int)$_POST['verification_id'];

    if (in_array($action, ['Approved', 'Rejected'])) {
        $stmt = $conn->prepare("UPDATE ngo_verifications SET verification_status = ? WHERE id = ?");
        $stmt->bind_param("si", $action, $verification_id);
        $stmt->execute();
        $stmt->close();
        // Optional: redirect to avoid resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Fetch all verification requests with NGO names
$result = $conn->query("
    SELECT nv.id, nv.ngo_id, nv.registration_number, nv.address, nv.contact_person, nv.contact_number, nv.verification_status, u.name 
    FROM ngo_verifications nv 
    JOIN users u ON nv.ngo_id = u.id 
    ORDER BY nv.created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - NGO Verifications</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f8f9fa;
    }
    .container {
      max-width: 1100px;
    }
    h2 {
      margin-bottom: 1.5rem;
    }
    table thead th {
      vertical-align: middle;
      text-align: center;
    }
    table tbody td {
      vertical-align: middle;
    }
    .btn-group form {
      margin-right: 5px;
    }
    .back-btn {
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <a href="admin_dashboard.php" class="btn btn-secondary back-btn">&larr; Back to Dashboard</a>
    <h2>NGO Verification Requests</h2>
    <div class="table-responsive shadow-sm bg-white rounded">
      <table class="table table-bordered table-hover mb-0">
        <thead class="table-success">
          <tr>
            <th>NGO Name</th>
            <th>Registration Number</th>
            <th>Address</th>
            <th>Contact Person</th>
            <th>Contact Number</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
              <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['registration_number']); ?></td>
                <td style="white-space: pre-line;"><?php echo htmlspecialchars($row['address']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td class="text-center">
                  <strong><?php echo htmlspecialchars($row['verification_status']); ?></strong>
                </td>
                <td class="text-center">
                  <?php if ($row['verification_status'] === 'Pending') : ?>
                    <div class="btn-group" role="group" aria-label="Actions">
                      <form method="POST" class="m-0 p-0 d-inline">
                        <input type="hidden" name="verification_id" value="<?php echo $row['id']; ?>">
                        <button name="action" value="Approved" class="btn btn-success btn-sm">Approve</button>
                      </form>
                      <form method="POST" class="m-0 p-0 d-inline">
                        <input type="hidden" name="verification_id" value="<?php echo $row['id']; ?>">
                        <button name="action" value="Rejected" class="btn btn-danger btn-sm">Reject</button>
                      </form>
                    </div>
                  <?php else : ?>
                    <em>No actions available</em>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-muted">No verification requests found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
