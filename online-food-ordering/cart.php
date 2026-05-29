<?php
// Cart page uses the session cart and allows item removal.
require_once 'includes/header.php';

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    if (isset($_SESSION['cart'][$remove_id])) {
        unset($_SESSION['cart'][$remove_id]);
    }
    header("Location: cart.php");
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<h2 class="mb-3">Your Cart</h2>
<?php if (empty($cart)): ?>
    <div class="alert alert-info">Your cart is empty. <a href="index.php">Go to menu</a></div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-danger">
                <tr>
                    <th>Food</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['name']); ?></td>
                        <td>$<?php echo number_format($item['price'], 2); ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                        <td><a href="cart.php?remove=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger remove-link">Remove</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th>$<?php echo number_format($total, 2); ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="text-end">
        <a href="index.php" class="btn btn-outline-secondary">Continue Shopping</a>
        <a href="checkout.php" class="btn btn-danger">Checkout</a>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
