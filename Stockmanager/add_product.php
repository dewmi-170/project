<?php
include('../db_connect.php');

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = floatval($_POST['price']);
    $stock_qty = intval($_POST['stock_qty']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, stock_qty, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdis", $product_name, $category, $price, $stock_qty, $status);

    if ($stmt->execute()) {
        $success = "✅ Product added successfully!";
    } else {
        $error = "❌ Failed to add product.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Product</title>
    <link href="../assets/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: #212529;
            padding: 20px;
            color: white;
            position: fixed;
        }

        .sidebar h4 {
            font-weight: bold;
            margin-bottom: 40px;
            color: #00e676;
        }

        .sidebar a {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 12px 0;
            font-size: 16px;
        }

        .sidebar a:hover {
            color: #ffffff;
        }

        .logout-link {
            position: absolute;
            bottom: 30px;
            width: 100%;
        }

        .logout-link a {
            color: #ff5252;
            font-weight: bold;
        }

        .main-content {
            margin-left: 260px;
            padding: 40px;
            width: 100%;
        }

        .form-container {
            max-width: 700px;
            margin: auto;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        h2 {
            text-align: center;
            color: #28a745;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
        }

        .btn {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div>
        <h4><i class="bi bi-box-seam-fill me-2"></i>Stock Manager</h4>
        <a href="#"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="inventory_monitoring.php"><i class="bi bi-boxes me-2"></i>Inventory Monitoring</a>
        <a href="stock_operations.php"class="active"><i class="bi bi-tools me-2"></i>Stock Operations</a>
        <a href="purchasing_oversight.php"><i class="bi bi-cart-check me-2"></i>Purchasing Oversight</a>
        <a href="sales_returns.php"><i class="bi bi-cash-coin me-2"></i>Sales & Returns</a>
        <a href="reports.php"><i class="bi bi-bar-chart-line me-2"></i>Reports</a>
        <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i>Notifications</a>
    </div>
    <div class="logout-link">
        <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Log Out</a>
    </div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="form-container">
        <h2>➕ Add New Product</h2>

        <?php if ($success): ?>
            <div class="alert alert-success text-center"><?php echo $success; ?></div>
            <script>
                setTimeout(() => {
                    window.location.href = "stock_operations.php";
                }, 1500);
            </script>
        <?php elseif ($error): ?>
            <div class="alert alert-danger text-center"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <input type="text" name="category" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Price (LKR)</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stock Quantity</label>
                <input type="number" name="stock_qty" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    <option value="In Stock">🟢 In Stock</option>
                    <option value="Low Stock">🟡 Low Stock</option>
                    <option value="Out of Stock">🔴 Out of Stock</option>
                </select>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success w-50 me-2">💾 Save</button>
                <a href="stock_operations.php" class="btn btn-secondary w-50">🔙 Back</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>
