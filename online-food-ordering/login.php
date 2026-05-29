<?php
// Customer/admin login page. Role-based redirect happens after successful login.
// Load only the database/session first so redirects can happen before any HTML output.
require_once 'config/db.php';

$flash_error = isset($_SESSION['flash_error']) ? $_SESSION['flash_error'] : null;
unset($_SESSION['flash_error']);

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $plain_password = $_POST['password'];

    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    $user = mysqli_fetch_assoc($user_query);

    // password_verify() checks the plain password against the saved hash.
    if ($user && password_verify($plain_password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_phone'] = $user['phone'];

        // Save login time so admin can see who logged in recently.
        $login_user_id = mysqli_real_escape_string($conn, $user['id']);
        mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = '$login_user_id'");

        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card premium-card shadow-lg border-0">
            <div class="card-body p-4 p-lg-5">
                <p class="text-uppercase text-danger small fw-bold mb-2">Step 2 of 4</p>
                <h2 class="card-title mb-2">Login</h2>
                <p class="text-muted">Login to unlock the menu, cart, checkout and order history.</p>
                <?php if ($flash_error): ?><div class="alert alert-warning"><?php echo $flash_error; ?></div><?php endif; ?>
                <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-danger btn-lg w-100">Login</button>
                </form>
                <p class="small text-muted mt-3 mb-0">Admin: admin@foodorder.com / admin123</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
