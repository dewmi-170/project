<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $invoice_id = trim($_POST['invoice_id']);
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  $reason = trim($_POST['reason']);
  $returned_by = $_SESSION['username'];

  if (!empty($invoice_id) && $product_id > 0 && $quantity > 0 && !empty($reason)) {
    $stmt = $conn->prepare("SELECT stock_qty FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $new_stock = $product['stock_qty'] + $quantity;

      $update = $conn->prepare("UPDATE products SET stock_qty = ? WHERE product_id = ?");
      $update->bind_param("ii", $new_stock, $product_id);
      $update->execute();

      $insert = $conn->prepare("INSERT INTO returns (product_id, invoice_id, quantity, reason, returned_by) VALUES (?, ?, ?, ?, ?)");
      $insert->bind_param("isiss", $product_id, $invoice_id, $quantity, $reason, $returned_by);
      $insert->execute();

      $message = "<div class='alert alert-success'>🔁 Return processed successfully!</div>";
    } else {
      $message = "<div class='alert alert-danger'>❌ Product not found.</div>";
    }
  } else {
    $message = "<div class='alert alert-danger'>⚠️ Invalid data entered.</div>";
  }
}

// Fetch product list
$products = [];
$result = $conn->query("SELECT product_id, product_name FROM products");
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

$return_history = $conn->query("
  SELECT r.*, p.product_name
  FROM returns r
  JOIN products p ON r.product_id = p.product_id
  ORDER BY r.return_id DESC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Process Return - GreenChoice Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      min-width: 220px;
      background-color: #343a40;
      padding-top: 20px;
    }
    .sidebar a {
      color: white;
      padding: 12px 20px;
      display: block;
      text-decoration: none;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }
    .sidebar h4 {
      color: white;
      text-align: center;
      margin-bottom: 20px;
    }
    .content {
      flex-grow: 1;
      padding: 30px;
      background-color: #f8f9fa;
      position: relative;
    }
    .back-btn {
      position: absolute;
      bottom: 30px;
      right: 30px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-shop me-2"></i>GreenChoice Admin</h4>
  <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="user_management.php"><i class="bi bi-person-gear me-2"></i> User Management</a>
  <a href="product_management.php"><i class="bi bi-box-seam me-2"></i> Product Management</a>
  <a href="inventory_oversight.php"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php" class="active"><i class="bi bi-currency-exchange me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <h2 class="mb-4">🧾 Process Return</h2>

  <?= $message ?>

  <form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
      <label for="invoice_id" class="form-label"><i class="bi bi-receipt"></i> Invoice ID</label>
      <input type="text" name="invoice_id" id="invoice_id" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="product_id" class="form-label"><i class="bi bi-box"></i> Select Product</label>
      <select name="product_id" id="product_id" class="form-select" required>
        <option value="">-- Choose Product --</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= $p['product_id'] ?>"><?= htmlspecialchars($p['product_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="quantity" class="form-label"><i class="bi bi-sort-numeric-up"></i> Quantity</label>
      <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
      <label for="reason" class="form-label"><i class="bi bi-chat-left-text"></i> Reason</label>
      <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
    </div>

    <button class="btn btn-warning w-100">
      <i class="bi bi-arrow-clockwise"></i> Submit Return
    </button>
  </form>

  <hr class="my-5" />

  <h4>📋 Return History</h4>
  <div class="table-responsive">
    <table class="table table-bordered table-hover mt-3">
      <thead class="table-dark">
        <tr>
          <th>Return ID</th>
          <th>Invoice ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Reason</th>
          <th>Returned By</th>
          <th>Returned At</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = $return_history->fetch_assoc()): ?>
          <tr>
  <td><?= $r['return_id'] ?></td>
  <td><?= htmlspecialchars($r['invoice_id']) ?></td>
  <td><?= htmlspecialchars($r['product_name']) ?></td>
  <td><?= $r['quantity'] ?></td>
  <td><?= htmlspecialchars($r['reason']) ?></td>
  <td><?= htmlspecialchars($r['returned_by']) ?></td>
  <td><?= $r['return_date'] ?></td>
</tr>

        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <a href="sales_return.php" class="btn btn-outline-secondary btn-sm back-btn">
    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
  </a>
</div>

</body>
</html>
