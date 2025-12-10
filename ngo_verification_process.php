<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'ngo') {
    header("Location: login.html?error=unauthorized");
    exit();
}

require 'db.php';

$ngo_id = $_SESSION['user_id'];
$registration_number = $_POST['registration_number'];
$address = $_POST['address'];
$contact_person = $_POST['contact_person'];
$contact_number = $_POST['contact_number'];

// Check if verification exists
$stmt = $conn->prepare("SELECT id FROM ngo_verifications WHERE ngo_id = ?");
$stmt->bind_param("i", $ngo_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Update existing record and reset status to Pending
    $stmt->close();
    $update_stmt = $conn->prepare("UPDATE ngo_verifications SET registration_number=?, address=?, contact_person=?, contact_number=?, verification_status='Pending' WHERE ngo_id=?");
    $update_stmt->bind_param("ssssi", $registration_number, $address, $contact_person, $contact_number, $ngo_id);
    $update_stmt->execute();
    $update_stmt->close();
} else {
    // Insert new verification request with status Pending
    $stmt->close();
    $insert_stmt = $conn->prepare("INSERT INTO ngo_verifications (ngo_id, registration_number, address, contact_person, contact_number, verification_status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $insert_stmt->bind_param("issss", $ngo_id, $registration_number, $address, $contact_person, $contact_number);
    $insert_stmt->execute();
    $insert_stmt->close();
}

$conn->close();

header("Location: ngo_dashboard.php?msg=Verification submitted successfully and is pending admin approval.");
exit();
