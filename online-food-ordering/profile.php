<?php
// Profile page shows the exact data the user used during registration and login.
require_once 'includes/auth.php';

$user_id = mysqli_real_escape_string($conn, $_SESSION['user_id']);
$user_result = mysqli_query($conn, "SELECT * FROM users WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($user_result);

$order_stats = mysqli_query($conn, "SELECT COUNT(*) AS total_orders, COALESCE(SUM(total_amount), 0) AS total_spent FROM orders WHERE user_id = '$user_id'");
$stats = mysqli_fetch_assoc($order_stats);
?>
<?php require_once 'includes/header.php'; ?>

<div class="profile-hero rounded-4 p-4 p-lg-5 text-white shadow-lg mb-4">
    <p class="text-uppercase small fw-bold mb-2">Customer Profile</p>
    <h1 class="display-6 fw-bold mb-0">Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>
    <p class="lead mb-0">Here is the information saved from your registration and login activity.</p>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card premium-card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h4 class="mb-3">Registered Information</h4>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <tr><th>Full Name</th><td><?php echo htmlspecialchars($user['name']); ?></td></tr>
                        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
                        <tr><th>Phone</th><td><?php echo htmlspecialchars($user['phone']); ?></td></tr>
                        <tr><th>Role</th><td><?php echo ucfirst($user['role']); ?></td></tr>
                        <tr><th>Registered At</th><td><?php echo $user['created_at']; ?></td></tr>
                        <tr><th>Last Login</th><td><?php echo $user['last_login'] ? $user['last_login'] : 'Current first login session'; ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card premium-card border-0 shadow-sm h-100">
            <div class="card-body p-4">
                <h4 class="mb-3">Ordering Summary</h4>
                <div class="summary-box mb-3">
                    <span>Total Orders</span>
                    <strong><?php echo $stats['total_orders']; ?></strong>
                </div>
                <div class="summary-box">
                    <span>Total Spent</span>
                    <strong>$<?php echo number_format($stats['total_spent'], 2); ?></strong>
                </div>
                <a href="orders.php" class="btn btn-danger w-100 mt-4">View My Orders</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
