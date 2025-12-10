<?php
$servername = "sql209.infinityfree.com";
$username = "if0_39116176";
$password = "8008851669";
$dbname = "if0_39116176_meal4all";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
