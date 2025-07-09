<?php include '../db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchasing Oversight | GreenChoice Stock Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      height: 100vh;
      background-color: #343a40;
      color: #28a745;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar h4 {
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      color: #ffffff;
      text-decoration: none;
      margin: 5px;
      padding: 10px;
      border-radius: 5px;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #28a745;
      color: white;
    }

    .container-fluid {
      margin-left: 270px;
      padding: 30px;
    }

    .section {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 25px;
      margin-bottom: 30px;
    }

    .btn-action {
      width: 80%;
      margin-top: 10px;
    }

    .back-btn-fixed {
      position: fixed;
      bottom: 20px;
      right: 30px;
      z-index: 999;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam-fill me-2"></i>Stock Manager</h4>
  <a href="stock_dashboard.php"><i class="bi bi-speedometer2"></i>Dashboard</a>
  <a href="inventory_monitoring.php"><i class="bi bi-boxes"></i>Inventory Monitoring</a>
  <a href="stock_operations.php"><i class="bi bi-tools"></i>Stock Operations</a>
  <a href="purchasing_oversight.php" class="active"><i class="bi bi-cart-check"></i>Purchasing Oversight</a>
  <a href="sales_returns.php"><i class="bi bi-cash-coin"></i>Sales & Returns</a>
  <a href="reports.php"><i class="bi bi-bar-chart-line"></i>Reports</a>
  <a href="notifications.php"><i class="bi bi-bell-fill"></i>Notifications</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right"></i>Log Out</a>
</div>

<!-- Main Content -->
<div class="container-fluid">
  <h2 class="text-success mb-4">🛒 Purchasing Oversight</h2>

  <!-- Submit Purchase Request -->
  <div class="section">
    <h3 class="section-title">📝 Submit Purchase Request</h3>
    <form action="submit_purchase.php" method="POST">
      <div class="row mb-3">
        <div class="col-md-4">
          <label class="form-label">Product Name</label>
          <input type="text" name="product" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Quantity</label>
          <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Expected Date</label>
          <input type="date" name="expected_date" class="form-control" required>
        </div>
      </div>
      <button type="submit" class="btn btn-success btn-action">Submit Request</button>
    </form>
  </div>

  <!-- Track Purchase Orders -->
  <div class="section">
    <h3 class="section-title">📋 Track PO Status</h3>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>PO ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Status</th>
          <th>Expected Delivery</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM purchase_requests ORDER BY id DESC");
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>#PO" . str_pad($row['id'], 3, '0', STR_PAD_LEFT) . "</td>
                  <td>{$row['product_name']}</td>
                  <td>{$row['quantity']}</td>
                  <td><span class='badge " .
                      ($row['status'] == 'Approved' ? 'bg-success' : ($row['status'] == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark')) .
                      "'>{$row['status']}</span></td>
                  <td>{$row['expected_date']}</td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ✅ Fixed Bottom-Right Back Button -->
<a href="stock_dashboard.php" class="btn btn-outline-secondary back-btn-fixed">
  </i> Back to Dashboard
</a>

</body>
</html>
