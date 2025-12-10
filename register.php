<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require 'db.php'; // db.php should contain your $conn = new mysqli(...);

// Receive and sanitize input
$role = $_POST['role'] ?? '';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$phone = trim($_POST['phone'] ?? '');

// Validate required fields
if (empty($role) || empty($name) || empty($email) || empty($password) || empty($phone)) {
    header("Location: register.html?error=missingfields");
    exit();
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.html?error=invalidemail");
    exit();
}

// Check for duplicate email
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    header("Location: register.html?error=emailtaken");
    exit();
}
$stmt->close();

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into users table
$stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $role);
if (!$stmt->execute()) {
    $stmt->close();
    header("Location: register.html?error=userinsertfail");
    exit();
}
$user_id = $stmt->insert_id;
$stmt->close();

// Handle Donor-specific fields
if ($role === 'donor') {
    $business_type = trim($_POST['business_type'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $stmt = $conn->prepare("INSERT INTO donors (user_id, business_type, location) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $business_type, $location);
    $stmt->execute();
    $stmt->close();
}

// Handle NGO-specific fields
if ($role === 'ngo') {
    $ngo_name = trim($_POST['ngo_name'] ?? '');
    $registration_number = trim($_POST['registration_number'] ?? '');
    $operating_area = trim($_POST['operating_area'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $certificate_path = '';

    // Handle certificate upload
    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === 0) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = basename($_FILES['certificate']['name']);
        $target_file = $upload_dir . uniqid() . "_" . $filename;

        if (move_uploaded_file($_FILES['certificate']['tmp_name'], $target_file)) {
            $certificate_path = $target_file;
        }
    }

    $stmt = $conn->prepare("INSERT INTO ngos (user_id, ngo_name, registration_number, certificate_path, operating_area, website) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $ngo_name, $registration_number, $certificate_path, $operating_area, $website);
    $stmt->execute();
    $stmt->close();
}

header("Location: login.html?success=registered");
exit();
?>
