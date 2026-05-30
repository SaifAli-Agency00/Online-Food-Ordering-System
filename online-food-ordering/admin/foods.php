<?php
// Admin food management page: add, edit, and delete food records.
require_once '../includes/admin_auth.php';

$edit_food = null;
$success = isset($_SESSION['admin_success']) ? $_SESSION['admin_success'] : null;
$error = isset($_SESSION['admin_error']) ? $_SESSION['admin_error'] : null;
unset($_SESSION['admin_success'], $_SESSION['admin_error']);

// Delete a food item by id. If it already belongs to orders, keep history safe.
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    $used_result = mysqli_query($conn, "SELECT id FROM order_items WHERE food_id = '$delete_id' LIMIT 1");

    if (mysqli_fetch_assoc($used_result)) {
        // Do not delete foods that are part of previous orders; hide them instead.
        mysqli_query($conn, "UPDATE foods SET status = 'unavailable' WHERE id = '$delete_id'");
        $_SESSION['admin_error'] = "This food is used in order history, so it was marked unavailable instead of deleted.";
    } else {
        mysqli_query($conn, "DELETE FROM foods WHERE id = '$delete_id'");
        $_SESSION['admin_success'] = "Food deleted successfully.";
    }


// Delete a food item by id.
if (isset($_GET['delete'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM foods WHERE id = '$delete_id'");
    header("Location: foods.php");
    exit();
}

// Load the selected food item into the form for editing.
if (isset($_GET['edit'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_result = mysqli_query($conn, "SELECT * FROM foods WHERE id = '$edit_id'");
    $edit_food = mysqli_fetch_assoc($edit_result);
}

// Add a new food item or update an existing one.
if (isset($_POST['save_food'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price = mysqli_real_escape_string($conn, trim($_POST['price']));
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if ($name === '' || $description === '' || $price === '') {
        $_SESSION['admin_error'] = "Name, description, and price are required.";
    } elseif (!is_numeric($price) || $price <= 0) {
        $_SESSION['admin_error'] = "Price must be greater than 0.";
    } elseif ($status !== 'available' && $status !== 'unavailable') {
        $_SESSION['admin_error'] = "Invalid food status selected.";
    } elseif (isset($_POST['food_id']) && $_POST['food_id'] !== '') {
        $food_id = mysqli_real_escape_string($conn, $_POST['food_id']);
        mysqli_query($conn, "UPDATE foods SET name = '$name', description = '$description', price = '$price', status = '$status' WHERE id = '$food_id'");
        $_SESSION['admin_success'] = "Food updated successfully.";
    } else {
        mysqli_query($conn, "INSERT INTO foods (name, description, price, status) VALUES ('$name', '$description', '$price', '$status')");
        $_SESSION['admin_success'] = "Food added successfully.";
    }

    header("Location: foods.php");
    exit();
    if (isset($_POST['food_id']) && $_POST['food_id'] !== '') {
        $food_id = mysqli_real_escape_string($conn, $_POST['food_id']);
        mysqli_query($conn, "UPDATE foods SET name = '$name', description = '$description', price = '$price', status = '$status' WHERE id = '$food_id'");
        $success = "Food updated successfully.";
    } else {
        mysqli_query($conn, "INSERT INTO foods (name, description, price, status) VALUES ('$name', '$description', '$price', '$status')");
        $success = "Food added successfully.";
    }
}

$foods = mysqli_query($conn, "SELECT * FROM foods ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Foods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="admin-page">
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link active" href="foods.php">Foods</a>
            <a class="nav-link" href="orders.php">Orders</a>
            <a class="nav-link" href="users.php">Users</a>
            <a class="nav-link active" href="foods.php">Manage Foods</a>
            <a class="nav-link" href="../index.php">View Site</a>
            <a class="nav-link" href="../logout.php">Logout</a>
        </div>
    </div>
</nav>
<div class="container py-4">
    <h1 class="mb-4">Manage Foods</h1>
    <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-warning"><?php echo $error; ?></div><?php endif; ?>
    <?php if (isset($success)): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4><?php echo $edit_food ? 'Edit Food' : 'Add Food'; ?></h4>
                    <form method="POST">
                        <input type="hidden" name="food_id" value="<?php echo $edit_food ? $edit_food['id'] : ''; ?>">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $edit_food ? htmlspecialchars($edit_food['name']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" required><?php echo $edit_food ? htmlspecialchars($edit_food['description']) : ''; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $edit_food ? $edit_food['price'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="available" <?php echo ($edit_food && $edit_food['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
                                <option value="unavailable" <?php echo ($edit_food && $edit_food['status'] === 'unavailable') ? 'selected' : ''; ?>>Unavailable</option>
                            </select>
                        </div>
                        <button type="submit" name="save_food" class="btn btn-danger w-100">Save Food</button>
                        <?php if ($edit_food): ?><a href="foods.php" class="btn btn-outline-secondary w-100 mt-2">Cancel Edit</a><?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Food List</h4>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead><tr><th>Name</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
                            <tbody>
                                <?php while ($food = mysqli_fetch_assoc($foods)): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($food['name']); ?></td>
                                        <td>$<?php echo number_format($food['price'], 2); ?></td>
                                        <td><?php echo ucfirst($food['status']); ?></td>
                                        <td>
                                            <a href="foods.php?edit=<?php echo $food['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="foods.php?delete=<?php echo $food['id']; ?>" class="btn btn-sm btn-danger delete-link">Delete</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="../assets/js/main.js"></script>
</body>
</html>
