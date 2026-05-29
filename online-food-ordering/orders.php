<?php
// My Orders page is protected so each customer can see only their own saved orders.
require_once 'includes/auth.php';

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$orders = mysqli_query($conn, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC");
?>
<?php require_once 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h2 class="mb-1">My Orders</h2>
        <p class="text-muted mb-0">Logged in as <?php echo htmlspecialchars($_SESSION['user_name']); ?>. Your placed orders appear here.</p>
    </div>
    <a href="index.php" class="btn btn-danger">Order More Food</a>
</div>

<?php if (mysqli_num_rows($orders) === 0): ?>
    <div class="alert alert-info">No orders found yet. Add food to your cart and complete checkout first.</div>
<?php else: ?>
    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-danger text-white d-flex justify-content-between flex-wrap gap-2">
                <span>Order #<?php echo $order['id']; ?></span>
                <span><?php echo $order['created_at']; ?></span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Total:</strong> $<?php echo number_format($order['total_amount'], 2); ?></div>
                    <div class="col-md-4"><strong>Status:</strong> <?php echo ucfirst($order['status']); ?></div>
                    <div class="col-md-4"><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></div>
                </div>
                <p><strong>Delivery Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>

                <?php
                // Load food items for this order.
                $order_id = mysqli_real_escape_string($conn, $order['id']);
                $items = mysqli_query($conn, "SELECT order_items.*, foods.name FROM order_items INNER JOIN foods ON order_items.food_id = foods.id WHERE order_items.order_id = '$order_id'");
                ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Food</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
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
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
