<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "sql209.infinityfree.com"; // or your actual DB host
$username = "if0_39116176";              
$password = "8008851669";          
$dbname = "if0_39116176_meal4all";       

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM users LIMIT 3";  // simple select from your users table
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Users table data:</h3><ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>ID: " . $row["id"] . ", Name: " . htmlspecialchars($row["name"]) . ", Email: " . htmlspecialchars($row["email"]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No records found.";
}

$conn->close();
?>
