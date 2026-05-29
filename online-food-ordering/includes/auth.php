<?php
// Include the database file so the session is available.
require_once __DIR__ . '/../config/db.php';

// Protect customer pages. If a user is not logged in, send them to login.php.
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
