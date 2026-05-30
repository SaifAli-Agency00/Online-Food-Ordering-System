<?php
// Separate admin login page. It only accepts users with the admin role.
require_once '../config/db.php';

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $plain_password = $_POST['password'];

    $admin_query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND role = 'admin'");
    $admin = mysqli_fetch_assoc($admin_query);

    if ($admin && password_verify($plain_password, $admin['password'])) {
        // Regenerate the session id after admin login to reduce session fixation risk.
        session_regenerate_id(true);

        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['user_name'] = $admin['name'];
        $_SESSION['user_role'] = $admin['role'];
        $_SESSION['user_phone'] = $admin['phone'];

        $admin_id = mysqli_real_escape_string($conn, $admin['id']);
        mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = '$admin_id'");

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid admin email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-login-bg">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="mb-3 text-center">Admin Login</h2>
                    <?php if (isset($error)): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-danger w-100">Login</button>
                    </form>
                    <p class="small text-muted mt-3 mb-0">Default: admin@foodorder.com / admin123</p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
