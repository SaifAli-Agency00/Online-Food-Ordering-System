<?php
// The header is shared by public pages to keep the layout consistent.
require_once __DIR__ . '/../config/db.php';

// Count cart items from the session cart array.
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}

// Check login once so the menu is easier to read.
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Food Ordering System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">FoodOrder</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <?php if ($is_logged_in): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart (<?php echo $cart_count; ?>)</a></li>
                    <li class="nav-item"><a class="nav-link" href="orders.php">My Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="admin/dashboard.php">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><span class="navbar-text text-white small ms-lg-2">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-light text-danger px-3 ms-lg-2" href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="container py-4">
