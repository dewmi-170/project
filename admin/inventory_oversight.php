<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

require_once('../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory Oversight | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7f6;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      height: 100vh;
      background-color:  #2f4f4f;
      color: white;
      padding-top: 30px;
      position: fixed;
      width: 250px;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 12px 20px;
      transition: background 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }

    .content {
      margin-left: 270px;
      padding: 40px 30px;
    }

    .section-title {
      margin-bottom: 30px;
    }

    .table thead {
      background-color: #28a745;
      color: white;
    }

    .btn-adjust {
      background-color: #ffc107;
      color: black;
    }

    .btn-adjust:hover {
      background-color: #e0a800;
    }

    .badge-low {
      background-color: #dc3545;
    }

    .badge-normal {
      background-color: #28a745;
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
      border-radius: 50px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .back-btn:hover {
      background-color: #218838;
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
  <a href="inventory_oversight.php" class="active"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <div class="d-flex justify-content-between align-items-center section-title">
    <h2><i class="bi bi-graph-up-arrow me-2"></i>Inventory Oversight</h2>
    <a href="adjust_stock.php" class="btn btn-adjust">
      <i class="bi bi-sliders"></i> Adjust Stock
    </a>
  </div>

  <table class="table table-bordered table-hover shadow-sm bg-white">
    <thead>
      <tr>
        <th>Product ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Stock Level</th>
        <th>Movement</th>
        <th>Status</th>
        <th>Last Updated</th>
        <th>Reason</th>
      </tr>
    </thead>
    <tbody>
      <?php
      
     $query = "SELECT p.product_id, p.product_name, p.category, p.stock_qty, sa.adjustment_qty, sa.created_at, sa.reason
          FROM products p
          LEFT JOIN (
              SELECT s1.*
              FROM stock_adjustments s1
              INNER JOIN (
                SELECT product_id, MAX(created_at) AS latest
                FROM stock_adjustments
                GROUP BY product_id
              ) s2 ON s1.product_id = s2.product_id AND s1.created_at = s2.latest
          ) sa ON p.product_id = sa.product_id
          ORDER BY p.product_id";

$result = $conn->query($query);

if (!$result) {
    die("Query Failed: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    $status = ($row['stock_qty'] < 10) ? 'Low Stock' : 'Normal';
    $badgeClass = ($status === 'Low Stock') ? 'badge-low' : 'badge-normal';
    $movement = $row['adjustment_qty'] ?? '0';
    $movementDisplay = $movement > 0 ? "+$movement" : "$movement";
    $lastUpdated = $row['created_at'] ?? 'N/A';
    $reason = $row['reason'] ?? 'N/A';

    echo "<tr>
            <td>{$row['product_id']}</td>
            <td>{$row['product_name']}</td>
            <td>{$row['category']}</td>
            <td>{$row['stock_qty']}</td>
            <td>{$movementDisplay}</td>
            <td><span class='badge $badgeClass'>{$status}</span></td>
            <td>{$lastUpdated}</td>
            <td>{$reason}</td>
          </tr>";
}


      $conn->close();
      ?>
    </tbody>
  </table>
</div>

<!-- Back Button Bottom Right -->
<div class="back-btn-fixed">
  <a href="admin_dashboard.php" class="back-btn">
    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
  </a>
</div>

</body>
</html>
