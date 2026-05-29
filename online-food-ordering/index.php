<?php
// Public landing + protected menu page.
// Users must login before they can see foods and add items to the cart.
require_once 'config/db.php';

$is_logged_in = isset($_SESSION['user_id']);

// Do not allow direct add-to-cart before login. Send the visitor to login first.
if (isset($_POST['add_to_cart']) && !$is_logged_in) {
    $_SESSION['flash_error'] = "Please login first, then you can add food to your cart.";
    header("Location: login.php");
    exit();
}

// Add a selected food item to the session cart only for logged-in users.
if (isset($_POST['add_to_cart']) && $is_logged_in) {
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

        $_SESSION['flash_success'] = "Food added to cart successfully. Open the cart to checkout.";
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

// Get available foods only after login so the flow is clear: register -> login -> menu -> cart -> checkout.
$foods_result = null;
if ($is_logged_in) {
    $foods_result = mysqli_query($conn, "SELECT * FROM foods WHERE status = 'available' ORDER BY id DESC");
}
?>
<?php require_once 'includes/header.php'; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if (!$is_logged_in): ?>
    <section class="hero rounded text-center text-white p-5 mb-4">
        <h1 class="display-5 fw-bold">Online Food Ordering Made Simple</h1>
        <p class="lead mb-0">Create an account, login, choose food, add to cart, and place your order.</p>
    </section>

    <div class="row g-4 align-items-stretch">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm step-card">
                <div class="card-body text-center">
                    <div class="step-number">1</div>
                    <h4>Register</h4>
                    <p>Create your customer account first so your orders are saved correctly.</p>
                    <a href="register.php" class="btn btn-danger">Create Account</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm step-card">
                <div class="card-body text-center">
                    <div class="step-number">2</div>
                    <h4>Login</h4>
                    <p>Login to unlock the food menu, cart, checkout, and your order history.</p>
                    <a href="login.php" class="btn btn-outline-danger">Login</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm step-card">
                <div class="card-body text-center">
                    <div class="step-number">3</div>
                    <h4>Order Food</h4>
                    <p>After login, add foods to your cart and checkout with delivery details.</p>
                    <a href="login.php" class="btn btn-outline-danger">Start Ordering</a>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <section class="hero rounded text-center text-white p-5 mb-4">
        <h1 class="display-5 fw-bold">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p class="lead mb-0">Select your favorite foods and add them to your cart.</p>
    </section>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="mb-0">Food Menu</h2>
        <a href="orders.php" class="btn btn-outline-danger">View My Orders</a>
    </div>

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
                            <button type="submit" name="add_to_cart" class="btn btn-danger">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
