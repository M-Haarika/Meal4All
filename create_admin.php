<?php
require 'db.php';

$name = "Admin";
$email = "admin2@meal4all.com";
$phone = "9876543210";
$role = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $password, $phone, $role);

if ($stmt->execute()) {
    echo "✅ Admin user created!";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
