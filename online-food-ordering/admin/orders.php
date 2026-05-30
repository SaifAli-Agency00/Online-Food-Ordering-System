<?php
// Admin order management page shows every order with customer details and order items.
require_once '../includes/admin_auth.php';

$success = isset($_SESSION['admin_success']) ? $_SESSION['admin_success'] : null;
$error = isset($_SESSION['admin_error']) ? $_SESSION['admin_error'] : null;
unset($_SESSION['admin_success'], $_SESSION['admin_error']);

if (isset($_POST['update_status'])) {
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $allowed_statuses = ['pending', 'confirmed', 'delivered', 'cancelled'];

    if (in_array($status, $allowed_statuses)) {
        mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE id = '$order_id'");
        $_SESSION['admin_success'] = "Order status updated successfully.";
    } else {
        $_SESSION['admin_error'] = "Invalid order status selected.";
    }

    header("Location: orders.php");
    exit();
}

$orders = mysqli_query($conn, "SELECT orders.*, users.name, users.email, users.phone AS user_phone FROM orders INNER JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
            <a class="nav-link active" href="orders.php">Orders</a>
            <a class="nav-link" href="users.php">Users</a>
            <a class="nav-link" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container py-4">
    <div class="admin-hero rounded-4 p-4 mb-4 text-white shadow-lg">
        <h1 class="mb-1">Manage Orders</h1>
        <p class="mb-0">Review final orders, customer contact data, delivery address, order items, and update status.</p>
    </div>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-warning"><?php echo $error; ?></div><?php endif; ?>

    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between flex-wrap gap-2">
                <span>Order #<?php echo $order['id']; ?> | <?php echo htmlspecialchars($order['name']); ?></span>
                <span><?php echo $order['created_at']; ?></span>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3"><strong>Email:</strong><br><?php echo htmlspecialchars($order['email']); ?></div>
                    <div class="col-md-3"><strong>Registered Phone:</strong><br><?php echo htmlspecialchars($order['user_phone']); ?></div>
                    <div class="col-md-3"><strong>Order Phone:</strong><br><?php echo htmlspecialchars($order['phone']); ?></div>
                    <div class="col-md-3"><strong>Total:</strong><br>$<?php echo number_format($order['total_amount'], 2); ?></div>
                    <div class="col-12"><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['address']); ?></div>
                </div>

                <?php
                $order_id = mysqli_real_escape_string($conn, $order['id']);
                $items = mysqli_query($conn, "SELECT order_items.*, foods.name FROM order_items INNER JOIN foods ON order_items.food_id = foods.id WHERE order_items.order_id = '$order_id'");
                ?>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light"><tr><th>Food</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>
                        <tbody>
                            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                                    <td>$<?php echo number_format($item['price'], 2); ?></td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <form method="POST" class="row g-2 align-items-end">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <div class="col-md-4">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo $order['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" name="update_status" class="btn btn-danger w-100">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
</div>
</body>
</html>
