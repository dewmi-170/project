<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $product_id = (int)$_POST['product_id'];
  $quantity = (int)$_POST['quantity'];
  $sale_price = (float)$_POST['sale_price'];
  $sold_by = $_SESSION['username'];

  if ($product_id > 0 && $quantity > 0 && $sale_price > 0) {
    $stmt = $conn->prepare("SELECT stock_qty FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $product = $result->fetch_assoc();
      $current_stock = (int)$product['stock_qty'];

      if ($current_stock >= $quantity) {
        $new_stock = $current_stock - $quantity;
        $total_price = $sale_price * $quantity;

        $update = $conn->prepare("UPDATE products SET stock_qty = ? WHERE product_id = ?");
        $update->bind_param("ii", $new_stock, $product_id);
        $update->execute();

        $insert = $conn->prepare("INSERT INTO sales (product_id, quantity,price, total_price, sold_by) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("iidds", $product_id, $quantity, $sale_price, $total_price, $sold_by);
        $insert->execute();

        $message = "<div class='alert alert-success'>✅ Sale recorded successfully!</div>";
      } else {
        $message = "<div class='alert alert-danger'>⚠️ Not enough stock available.</div>";
      }
    } else {
      $message = "<div class='alert alert-danger'>❌ Product not found.</div>";
    }
  } else {
    $message = "<div class='alert alert-danger'>❗ Please fill all fields with valid data.</div>";
  }
}

$products = [];
$result = $conn->query("SELECT product_id, product_name, stock_qty FROM products");
while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Process Sale - GreenChoice Admin</title>
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
    .sidebar a:hover {
      background-color: #495057;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }
    .content {
      flex-grow: 1;
      padding: 30px;
      background-color: #f8f9fa;
      position: relative; /* To allow absolute position inside */
    }
    .sidebar h4 {
      color: #ffffff;
      text-align: center;
      margin-bottom: 20px;
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
  <h4><i class="bi bi-shop-window me-2"></i>GreenChoice Admin</h4>
  <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="user_management.php"><i class="bi bi-person-gear me-2"></i> User Management</a>
  <a href="product_management.php"><i class="bi bi-box-seam me-2"></i> Product Management</a>
  <a href="inventory_oversight.php"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php" class="active"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <h2 class="mb-4"> Process Sale</h2>

  <?= $message ?>

  <form method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
      <label for="product_id" class="form-label"><i class="bi bi-box"></i> Select Product</label>
      <select name="product_id" id="product_id" class="form-select" required>
        <option value="">-- Choose Product --</option>
        <?php foreach ($products as $p): ?>
          <option value="<?= $p['product_id'] ?>" data-stock="<?= $p['stock_qty'] ?>">
            <?= htmlspecialchars($p['product_name']) ?> (Stock: <?= $p['stock_qty'] ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label for="quantity" class="form-label"><i class="bi bi-sort-numeric-up"></i> Quantity</label>
      <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
    </div>

    <div class="mb-3">
      <label for="sale_price" class="form-label"><i class="bi bi-tag-fill"></i> Sale Price (Rs)</label>
      <input type="number" name="sale_price" id="sale_price" step="0.01" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success w-100">
      <i class="bi bi-check-circle"></i> Submit Sale
    </button>
  </form>

  <!-- Back Button at bottom-right -->
  <a href="sales_return.php" class="btn btn-sm btn-outline-secondary back-btn">
    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
  </a>
</div>

</body>
</html>
