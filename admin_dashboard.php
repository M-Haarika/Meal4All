<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: login.html?error=unauthorized");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Meal4All</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Meal4All Admin</h1>
        <nav>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main class="container">
        <h2>Welcome Admin, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
        <p>Here you can manage NGOs, donors, and food requests.</p>

        <!-- Add your admin functionality here -->
         <div>
      <a href="admin_verifications.php" class="btn btn-primary me-3">Manage NGO Verifications</a>
      <a href="donations.php" class="btn btn-primary me-3">View Donations</a>
      <!-- You can add more admin links here -->
</div>
    </main>
    <footer>
        <p>&copy; 2025 Meal4All. Admin Panel.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
