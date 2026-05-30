<?php
// Admin users report shows who registered, contact data, login time, and order totals.
require_once '../includes/admin_auth.php';

$users = mysqli_query($conn, "SELECT users.*, COUNT(orders.id) AS order_count, COALESCE(SUM(orders.total_amount), 0) AS total_spent FROM users LEFT JOIN orders ON users.id = orders.user_id WHERE users.role = 'user' GROUP BY users.id ORDER BY users.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-page">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="foods.php">Foods</a>
            <a class="nav-link" href="orders.php">Orders</a>
            <a class="nav-link active" href="users.php">Users</a>
            <a class="nav-link" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container py-4">
    <div class="admin-hero rounded-4 p-4 mb-4 text-white shadow-lg">
        <h1 class="mb-1">Registered Users</h1>
        <p class="mb-0">See customer name, email, phone, registration time, last login, and order activity.</p>
    </div>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered At</th>
                            <th>Last Login</th>
                            <th>Orders</th>
                            <th>Total Spent</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td><?php echo $user['created_at']; ?></td>
                                <td><?php echo $user['last_login'] ? $user['last_login'] : 'Not logged in yet'; ?></td>
                                <td><?php echo $user['order_count']; ?></td>
                                <td>$<?php echo number_format($user['total_spent'], 2); ?></td>
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
