<?php
// Start the session on every page that includes this file.
// Sessions are used for login status and the shopping cart.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection settings for local XAMPP/WAMP/MAMP/LAMP setup.
$host = "localhost";
$username = "root";
$password = "";
$database = "food_ordering_system";

// Connect to MySQL using mysqli_connect() as requested.
$conn = mysqli_connect($host, $username, $password, $database);

// Stop the page with a beginner-friendly message if the database fails to connect.
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
