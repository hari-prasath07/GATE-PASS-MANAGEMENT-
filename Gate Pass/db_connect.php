<?php
$servername = "localhost";  // Usually localhost for local development
$username = "root";         // Default username for MySQL in WAMP
$password = "newpassword";             // Default password is empty for root in WAMP
$dbname = "gatepass_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";
?>
