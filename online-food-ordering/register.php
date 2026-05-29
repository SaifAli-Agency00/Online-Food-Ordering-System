<?php
// Registration page for new customers.
// Load only the database/session first so form processing happens before HTML output.
require_once 'config/db.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $plain_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($name === '' || $email === '' || $phone === '' || $plain_password === '' || $confirm_password === '') {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($plain_password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($plain_password !== $confirm_password) {
        $error = "Password and confirm password do not match.";
    } else {
        // Check if this email is already registered.
        $check_user = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

        if (mysqli_fetch_assoc($check_user)) {
            $error = "Email is already registered. Please login instead.";
        } else {
            // Store a secure password hash instead of the plain password.
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
            $insert_user = mysqli_query($conn, "INSERT INTO users (name, email, phone, password, role) VALUES ('$name', '$email', '$phone', '$hashed_password', 'user')");

            if ($insert_user) {
                $_SESSION['flash_error'] = "Registration successful. Please login to view the food menu.";
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center align-items-center">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="auth-info-card p-4 p-lg-5 text-white rounded-4 shadow-lg">
            <p class="text-uppercase small fw-bold mb-2">Step 1 of 4</p>
            <h1 class="display-6 fw-bold">Create your food ordering account</h1>
            <p class="lead mb-0">Register with your name, email, phone and password. After login, the premium menu, cart, checkout and order history will unlock.</p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card premium-card shadow-lg border-0">
            <div class="card-body p-4 p-lg-5">
                <h2 class="card-title mb-2">Register</h2>
                <p class="text-muted">Your details will be visible in admin reports and your profile.</p>
                <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST" id="registerForm">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control form-control-lg" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control form-control-lg" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>" placeholder="Example: +1555010101" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control form-control-lg" minlength="6" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control form-control-lg" minlength="6" required>
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-danger btn-lg w-100">Create Account</button>
                </form>
                <p class="mt-3 mb-0 text-center">Already registered? <a href="login.php">Login here</a></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
