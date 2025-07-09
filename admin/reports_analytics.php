<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reports & Analytics | GreenChoice Market</title>
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

    
    .content {
      margin-left: 260px;
      padding: 40px;
      position: relative;
      min-height: 100vh;
      padding-bottom: 80px; /* space for back button */
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .card-title {
      font-weight: 600;
    }
    .btn-green {
      background-color: #28a745;
      color: white;
      font-weight: bold;
    }
    .btn-green:hover {
      background-color: #218838;
    }
    .btn-back {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #28a745;
      color: white;
      border-radius: 30px;
      padding: 10px 20px;
      font-weight: bold;
      box-shadow: 0 4px 8px rgba(0,128,0,0.2);
      transition: 0.3s;
      z-index: 1000;
    }
    .btn-back:hover {
      background-color: #218838;
      color: white;
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
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php" class="active"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="content">
  <h2 class="mb-4 text-success"><i class="bi bi-bar-chart-line-fill me-2"></i>Reports & Analytics</h2>

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5 class="card-title"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Sales Reports</h5>
        <p>View detailed daily, weekly or monthly sales reports.</p>
        <a href="sales_report.php" class="btn btn-green">View Reports</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5 class="card-title"><i class="bi bi-box-seam me-2 text-warning"></i>Inventory Trends</h5>
        <p>Analyze inventory trends and stock movement.</p>
        <a href="inventory_trends.php" class="btn btn-green">Analyze Trends</a>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5 class="card-title"><i class="bi bi-cash-stack me-2 text-primary"></i>Revenue Insights</h5>
        <p>Check profit margins and revenue performance.</p>
        <a href="revenue_insights.php" class="btn btn-green">View Insights</a>
      </div>
    </div>
  </div>
</div>

<!-- Back Button -->
<a href="admin_dashboard.php" class="btn btn-back">
  <i class="bi bi-arrow-left-circle me-2"></i>Back to Dashboard
</a>

</body>
</html>
