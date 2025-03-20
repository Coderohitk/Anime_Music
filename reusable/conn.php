<?php
// Database connection settings
$servername = "localhost"; // Your server address, typically localhost
$username = "root";        // Your database username
$password = "";            // Your database password (blank if not set)
$dbname = "anime_music"; // The name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
