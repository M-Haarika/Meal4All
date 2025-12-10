<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'db.php';

// Check if donor is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'donor') {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Use user_id consistently from session
    $user_id = $_SESSION['user_id'];

    // Get and sanitize inputs
    $food_description = trim($_POST['food_description'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    $contact = trim($_POST['contact'] ?? '');
    $location = trim($_POST['location'] ?? '');

    // Basic validation
    if (!$food_description || !$quantity || !$date || !$time || !$contact || !$location) {
        header("Location: donor_dashboard.php?error=emptyfields");
        exit();
    }

    // Prepare statement using user_id (not donor_id)
    $stmt = $conn->prepare("INSERT INTO donations (user_id, food_description, quantity, pickup_date, pickup_time, contact_info, location) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        // Handle prepare error gracefully
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters (s = string, i = integer)
    $stmt->bind_param("isissss", $user_id, $food_description, $quantity, $date, $time, $contact, $location);

    // Execute statement and check for errors
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: donor_dashboard.php?success=donation_submitted");
        exit();
    } else {
        $stmt->close();
        header("Location: donor_dashboard.php?error=sqlerror");
        exit();
    }
} else {
    header("Location: donor_dashboard.php");
    exit();
}
?>
