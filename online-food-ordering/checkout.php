<?php
// Checkout is protected so only logged-in users can place final orders.
require_once 'includes/auth.php';

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$current_user = mysqli_fetch_assoc($user_result);
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
<?php require_once 'includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <p class="text-uppercase text-danger small fw-bold mb-1">Step 4 of 4</p>
        <h2 class="mb-0">Final Order Confirmation</h2>
        <p class="text-muted mb-0">Review your customer details, delivery details, and cart before placing the final order.</p>
    </div>
    <a href="cart.php" class="btn btn-outline-danger">Back to Cart</a>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success shadow-sm"><?php echo $success; ?></div>
    <a href="orders.php" class="btn btn-danger">View My Orders</a>
    <a href="index.php" class="btn btn-outline-danger">Back to Menu</a>

<h2 class="mb-3">Checkout</h2>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
    <a href="index.php" class="btn btn-danger">Back to Menu</a>
<?php elseif (empty($cart)): ?>
    <div class="alert alert-info">Your cart is empty. <a href="index.php">Choose food first</a>.</div>
<?php else: ?>
    <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card premium-card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h4>Customer Details</h4>
                    <div class="row g-3">
                        <div class="col-md-4"><strong>Name:</strong><br><?php echo htmlspecialchars($current_user['name']); ?></div>
                        <div class="col-md-4"><strong>Email:</strong><br><?php echo htmlspecialchars($current_user['email']); ?></div>
                        <div class="col-md-4"><strong>Phone:</strong><br><?php echo htmlspecialchars($current_user['phone']); ?></div>
                    </div>
                </div>
            </div>
            <div class="card premium-card shadow-sm border-0">
                <div class="card-body p-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Delivery Details</h4>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Delivery Address</label>
                            <textarea name="address" class="form-control form-control-lg" rows="4" required><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control form-control-lg" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : htmlspecialchars($current_user['phone']); ?>" required>
                        </div>
                        <button type="submit" name="place_order" class="btn btn-danger btn-lg w-100">Confirm Final Order</button>
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
            <div class="card premium-card shadow-sm border-0 sticky-summary">
                <div class="card-body p-4">
                    <h4>Final Order Summary</h4>
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Order Summary</h4>
                    <?php foreach ($cart as $item): ?>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                            <span>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <div class="d-flex justify-content-between pt-3 fw-bold fs-5">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <p class="small text-muted mt-3 mb-0">Press "Confirm Final Order" only when all details are correct.</p>
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
