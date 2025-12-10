<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "sql209.infinityfree.com"; // replace with your actual host if different
$username = "if0_39116176";              // your DB username
$password = "8008851669";          // your DB password
$dbname = "if0_39116176_meal4all";       // your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . ", Name: " . $row["name"] . ", Email: " . $row["email"] . "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
