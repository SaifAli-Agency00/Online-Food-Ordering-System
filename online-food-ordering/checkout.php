<?php
// Checkout is protected so only logged-in users can place orders.
require_once 'includes/auth.php';
require_once 'includes/header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}

if (isset($_POST['place_order'])) {
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $user_id = $_SESSION['user_id'];

    if (empty($cart)) {
        $error = "Your cart is empty.";
    } elseif ($address === '' || $phone === '') {
        $error = "Address and phone are required.";
    } else {
        // Save the order first, then save each food item in order_items.
        $order_sql = "INSERT INTO orders (user_id, total_amount, address, phone, status) VALUES ('$user_id', '$total', '$address', '$phone', 'pending')";
        $order_result = mysqli_query($conn, $order_sql);

        if ($order_result) {
            $order_id = mysqli_insert_id($conn);

            foreach ($cart as $item) {
                $food_id = $item['id'];
                $quantity = $item['quantity'];
                $price = $item['price'];
                mysqli_query($conn, "INSERT INTO order_items (order_id, food_id, quantity, price) VALUES ('$order_id', '$food_id', '$quantity', '$price')");
            }

            unset($_SESSION['cart']);
            $success = "Order placed successfully. Your order number is #" . $order_id . ".";
            $cart = [];
        } else {
            $error = "Order could not be saved. Please try again.";
        }
    }
}
?>

<h2 class="mb-3">Checkout</h2>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <a href="orders.php" class="btn btn-danger">View My Orders</a>
    <a href="index.php" class="btn btn-outline-danger">Back to Menu</a>
<?php elseif (empty($cart)): ?>
    <div class="alert alert-info">Your cart is empty. <a href="index.php">Choose food first</a>.</div>
<?php else: ?>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Delivery Details</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Delivery Address</label>
                            <textarea name="address" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <button type="submit" name="place_order" class="btn btn-danger">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Order Summary</h4>
                    <?php foreach ($cart as $item): ?>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div class="d-flex justify-content-between pt-3 fw-bold">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
