<?php
// Public menu page. Everyone can view food items and add them to the cart.
// Load only the database/session first so add-to-cart redirects happen before HTML output.
require_once 'config/db.php';

// Add a selected food item to the session cart.
if (isset($_POST['add_to_cart'])) {
    $food_id = mysqli_real_escape_string($conn, $_POST['food_id']);
    $quantity = (int) $_POST['quantity'];

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Fetch the food from the database to make sure the price and name are trusted.
    $food_query = mysqli_query($conn, "SELECT * FROM foods WHERE id = '$food_id' AND status = 'available'");
    $food = mysqli_fetch_assoc($food_query);

    if ($food) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // If the item already exists in cart, increase quantity.
        if (isset($_SESSION['cart'][$food_id])) {
            $_SESSION['cart'][$food_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$food_id] = [
                'id' => $food['id'],
                'name' => $food['name'],
                'price' => $food['price'],
                'quantity' => $quantity
            ];
        }

        $_SESSION['flash_success'] = "Food added to cart successfully.";
    } else {
        $_SESSION['flash_error'] = "Food item is not available.";
    }

    // Redirect after POST to stop duplicate cart additions when the page is refreshed.
    header("Location: index.php");
    exit();
}

$success = isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : null;
$error = isset($_SESSION['flash_error']) ? $_SESSION['flash_error'] : null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Get all available foods for the menu.
$foods_result = mysqli_query($conn, "SELECT * FROM foods WHERE status = 'available' ORDER BY id DESC");
?>
<?php require_once 'includes/header.php'; ?>

<section class="hero rounded text-center text-white p-5 mb-4">
    <h1 class="display-5 fw-bold">Order Delicious Food Online</h1>
    <p class="lead mb-0">Choose your favorite meals and checkout in a few simple steps.</p>
</section>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<h2 class="mb-3">Food Menu</h2>
<div class="row g-4">
    <?php while ($food = mysqli_fetch_assoc($foods_result)): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm food-card">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
                    <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars($food['description']); ?></p>
                    <p class="fw-bold text-danger fs-5">$<?php echo number_format($food['price'], 2); ?></p>
                    <form method="POST" class="d-flex gap-2">
                        <input type="hidden" name="food_id" value="<?php echo $food['id']; ?>">
                        <input type="number" name="quantity" value="1" min="1" class="form-control quantity-input">
                        <button type="submit" name="add_to_cart" class="btn btn-danger">Add</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
