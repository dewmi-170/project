<?php
session_start();
include '../db_connect.php';

// Role validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

// Fetch products
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Management | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7f6;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      height: 100%;
      background-color: #2f4f4f;
      padding-top: 20px;
      color: white;
      z-index: 1000;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      font-size: 16px;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }

    .container {
      margin-left: 230px;
      padding: 40px 20px;
    }

    .table thead {
      background-color: #28a745;
      color: white;
    }

    .btn-add {
      background-color: #28a745;
      color: white;
    }

    .btn-add:hover {
      background-color: #218838;
    }

    .page-title {
      margin-bottom: 30px;
    }

    .action-btns .btn {
      margin-right: 5px;
    }

    .back-btn-fixed {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 999;
    }

    .back-btn {
      background-color: #28a745;
      color: white;
      padding: 10px 18px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      border-radius: 50px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .back-btn:hover {
      background-color: #218838;
      color: white;
    }

    .table {
      background-color: white;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-shop-window me-2"></i>GreenChoice Admin</h4>
  <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="user_management.php"><i class="bi bi-person-gear me-2"></i> User Management</a>
  <a href="product_management.php" class="active"><i class="bi bi-box-seam me-2"></i> Product Management</a>
  <a href="inventory_oversight.php"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="container">
  <div class="d-flex justify-content-between align-items-center page-title">
    <h2> Product Management</h2>
    <a href="add_product.php" class="btn btn-add">
      <i class="bi bi-plus-circle"></i> Add Product
    </a>
  </div>

  <table class="table table-bordered table-hover shadow-sm">
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Price (Rs)</th>
        <th>Stock Qty</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['product_id'] ?></td>
            <td><?= $row['product_name'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= number_format($row['price'], 2) ?></td>
            <td><?= $row['stock_qty'] ?></td>
            <td>
              <span class="badge <?= $row['status'] === 'Active' ? 'bg-success' : 'bg-secondary' ?>">
                <?= $row['status'] ?>
              </span>
            </td>
            <td class="action-btns">
              <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="delete_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" class="text-center">No products found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Fixed Back Button -->
<div class="back-btn-fixed">
  <a href="admin_dashboard.php" class="back-btn">
    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
  </a>
</div>

</body>
</html>
