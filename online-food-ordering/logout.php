<?php
// Log the current user out and return to the menu.
require_once 'config/db.php';
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>
