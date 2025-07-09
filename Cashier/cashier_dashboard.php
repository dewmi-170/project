<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Cashier Dashboard | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      display: flex;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0fdf4;
      height: 100vh;
      overflow: hidden;
    }

    .sidebar {
      width: 250px;
      background-color: #256029;
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 20px;
      position: fixed;
      height: 100vh;
    }

    .sidebar h4 {
      text-align: center;
      font-size: 1.6rem;
      font-weight: bold;
      margin-bottom: 40px;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #fff;
      padding: 12px 15px;
      margin-bottom: 12px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .sidebar a i {
      margin-right: 10px;
      font-size: 1.3rem;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1a4f20;
      padding-left: 20px;
    }

    .logout-btn {
      background-color: #c62828;
      color: #fff;
      font-weight: bold;
      text-align: center;
      padding: 10px 0;
      border-radius: 8px;
      transition: background-color 0.3s ease;
      text-decoration: none;
    }

    .logout-btn:hover {
      background-color: #b71c1c;
    }

    .main-content {
      margin-left: 250px;
      padding: 30px;
      width: calc(100% - 250px);
      overflow-y: auto;
      height: 100vh;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
      transition: 0.3s;
    }

    .card:hover {
      transform: translateY(-3px);
    }

    .card h5 {
      color: #256029;
    }

    .btn-outline-primary {
      color: #256029;
      border-color: #256029;
    }

    .btn-outline-primary:hover {
      background-color: #256029;
      color: white;
    }

    .card-footer {
      text-align: center;
      padding: 20px 0;
      color: #555;
      margin-top: 50px;
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <div>
      <h4><i class="bi bi-person-badge"></i> Cashier Panel</h4>
      <a href="cashier_dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="sales_billing.php"><i class="bi bi-credit-card"></i> Sales & Billing</a>
      <a href="stock_view.php"><i class="bi bi-box-seam"></i> Stock View</a>
      <a href="returns_refunds.php"><i class="bi bi-arrow-return-left"></i> Returns & Refunds</a>
      <a href="sales_reports.php"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
      <a href="alerts.php"><i class="bi bi-bell"></i> Alerts</a>
    </div>
    <a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </div>

  <div class="main-content">
    <div class="topbar">
      <h3>Welcome, Cashier</h3>
      <span><i class="bi bi-bell-fill fs-5 text-success"></i></span>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5>💳 Sales & Billing</h5>
          <p>Process sales and generate invoices</p>
          <a href="sales_billing.php" class="btn btn-outline-primary">Start Billing</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5>🔍 Stock View</h5>
          <p>Check item availability at checkout</p>
          <a href="stock_view.php" class="btn btn-outline-primary">View Stock</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5>↩️ Returns & Refunds</h5>
          <p>Handle customer returns with approvals</p>
          <a href="returns_refunds.php" class="btn btn-outline-primary">Process Return</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5>📋 Reports</h5>
          <p>View end-of-shift sales summaries</p>
          <a href="sales_reports.php" class="btn btn-outline-primary">View Reports</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5>🔔 Alerts</h5>
          <p>Receive important system messages</p>
          <a href="alerts.php" class="btn btn-outline-primary">View Alerts</a>
        </div>
      </div>
    </div>

    <div class="card-footer">
      &copy; 2025 GreenChoice Market - Cashier Panel
    </div>
  </div>

</body>
</html>
