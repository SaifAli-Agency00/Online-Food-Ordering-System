<?php
// Admin dashboard is protected by role-based authentication.
require_once '../includes/admin_auth.php';

$food_count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM foods");
$food_count = mysqli_fetch_assoc($food_count_result);
$order_count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$order_count = mysqli_fetch_assoc($order_count_result);
$user_count_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'user'");
$user_count = mysqli_fetch_assoc($user_count_result);
$revenue_result = mysqli_query($conn, "SELECT COALESCE(SUM(total_amount), 0) AS total FROM orders");
$revenue = mysqli_fetch_assoc($revenue_result);
$recent_orders = mysqli_query($conn, "SELECT orders.*, users.name, users.email, users.phone FROM orders INNER JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC LIMIT 6");
$recent_users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'user' ORDER BY id DESC LIMIT 6");
$recent_orders = mysqli_query($conn, "SELECT orders.*, users.name FROM orders INNER JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="foods.php">Manage Foods</a>
            <a class="nav-link" href="../index.php">View Site</a>
            <a class="nav-link" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container py-4">
    <div class="admin-hero rounded-4 p-4 mb-4 text-white shadow-lg">
        <h1 class="mb-1">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p class="mb-0">Track customers, login times, orders, revenue, and food items from one clean backend.</p>
    </div>
    <div class="row g-4 mb-4">
        <div class="col-md-3"><div class="card dashboard-card"><div class="card-body"><h6>Total Foods</h6><p class="display-6"><?php echo $food_count['total']; ?></p></div></div></div>
        <div class="col-md-3"><div class="card dashboard-card"><div class="card-body"><h6>Total Orders</h6><p class="display-6"><?php echo $order_count['total']; ?></p></div></div></div>
        <div class="col-md-3"><div class="card dashboard-card"><div class="card-body"><h6>Customers</h6><p class="display-6"><?php echo $user_count['total']; ?></p></div></div></div>
        <div class="col-md-3"><div class="card dashboard-card"><div class="card-body"><h6>Revenue</h6><p class="display-6">$<?php echo number_format($revenue['total'], 2); ?></p></div></div></div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Recent Orders</h4>
                        <a href="orders.php" class="btn btn-sm btn-danger">Manage All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead><tr><th>ID</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo htmlspecialchars($order['name']); ?><br><small><?php echo htmlspecialchars($order['email']); ?></small></td>
                                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                                        <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td><span class="badge bg-warning text-dark"><?php echo ucfirst($order['status']); ?></span></td>
                                        <td><?php echo $order['created_at']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">New Customers</h4>
                        <a href="users.php" class="btn btn-sm btn-danger">View Users</a>
                    </div>
                    <?php while ($user = mysqli_fetch_assoc($recent_users)): ?>
                        <div class="admin-user-row">
                            <strong><?php echo htmlspecialchars($user['name']); ?></strong><br>
                            <small><?php echo htmlspecialchars($user['email']); ?> | <?php echo htmlspecialchars($user['phone']); ?></small><br>
                            <small>Registered: <?php echo $user['created_at']; ?> | Last login: <?php echo $user['last_login'] ? $user['last_login'] : 'Not logged in yet'; ?></small>
                        </div>
                    <?php endwhile; ?>
                </div>
    <h1 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <div class="row g-4 mb-4">
        <div class="col-md-4"><div class="card dashboard-card"><div class="card-body"><h5>Total Foods</h5><p class="display-6"><?php echo $food_count['total']; ?></p></div></div></div>
        <div class="col-md-4"><div class="card dashboard-card"><div class="card-body"><h5>Total Orders</h5><p class="display-6"><?php echo $order_count['total']; ?></p></div></div></div>
        <div class="col-md-4"><div class="card dashboard-card"><div class="card-body"><h5>Customers</h5><p class="display-6"><?php echo $user_count['total']; ?></p></div></div></div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4>Recent Orders</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($recent_orders)): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['name']); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td><?php echo ucfirst($order['status']); ?></td>
                                <td><?php echo $order['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
