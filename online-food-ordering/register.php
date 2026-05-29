<?php
// Registration page for new customers.
// Load only the database/session first so form processing happens before HTML output.
require_once 'config/db.php';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $plain_password = $_POST['password'];

    if ($name === '' || $email === '' || $plain_password === '') {
        $error = "All fields are required.";
    } else {
        // Check if this email is already registered.
        $check_user = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");

        if (mysqli_fetch_assoc($check_user)) {
            $error = "Email is already registered.";
        } else {
            // Store a secure password hash instead of the plain password.
            $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);
            $insert_user = mysqli_query($conn, "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_password', 'user')");

            if ($insert_user) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="card-title mb-3">Create Account</h2>
                <?php if (isset($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
                <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-danger w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
