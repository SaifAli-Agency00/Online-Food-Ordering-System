<?php
// Include the database file so the session is available.
require_once __DIR__ . '/../config/db.php';

// Protect admin pages. Only logged-in users with the admin role can continue.
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
