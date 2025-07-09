<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'stock_manager') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Stock Manager Dashboard | GreenChoice Market</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
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
      margin: 10px 0;
      padding: 10px;
      border-radius: 5px;
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #28a745;
      padding-left: 15px;
      color: #fff;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .notification-icon {
      font-size: 20px;
      color: #198754;
    }

    .logout-top {
      color: #fff;
      background-color: #dc3545;
      border: none;
      padding: 6px 10px;
      border-radius: 5px;
      text-decoration: none;
      font-size: 14px;
    }

    .logout-top:hover {
      background-color: #bb2d3b;
    }

    .card-footer {
      margin-left: 250px;
      padding: 15px;
      background-color: #e9ecef;
      text-align: center;
    }

    .widget {
      background: #ffffff;
      border-left: 5px solid #198754;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .widget h4 {
      font-size: 18px;
      margin-bottom: 10px;
      color: #198754;
    }

    .widget-value {
      font-size: 22px;
      font-weight: bold;
    }

    .dashboard-title {
      font-weight: 600;
      color: #198754;
    }

    .card i {
      font-size: 1.5rem;
      color: #198754;
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }
      .main-content, .card-footer {
        margin-left: 0 !important;
      }
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <h4><i class="bi bi-box-seam-fill me-2"></i>Stock Manager</h4>
      <a href="#" class="active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
      <a href="inventory_monitoring.php"><i class="bi bi-boxes me-2"></i>Inventory Monitoring</a>
      <a href="stock_operations.php"><i class="bi bi-tools me-2"></i>Stock Operations</a>
      <a href="purchasing_oversight.php"><i class="bi bi-cart-check me-2"></i>Purchasing Oversight</a>
      <a href="sales_returns.php"><i class="bi bi-cash-coin me-2"></i>Sales & Returns</a>
      <a href="reports.php"><i class="bi bi-bar-chart-line me-2"></i>Reports</a>
      <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i>Notifications</a>
    </div>
    <div class="logout-link mt-auto">
      <a href="../logout.php" class="logout"><i class="bi bi-box-arrow-right me-2"></i>Log Out</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h3 class="dashboard-title">Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
      <div>
        <i class="bi bi-bell-fill notification-icon me-3"></i>
        <a href="../logout.php" class="logout-top"><i class="bi bi-box-arrow-right me-1"></i>Log Out</a>
      </div>
    </div>

    <!-- Dashboard Widgets -->
    <div class="row">
      <div class="col-md-6 col-lg-3">
        <div class="widget">
          <h4>Total Products</h4>
          <div class="widget-value" id="total-products">Loading...</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="widget">
          <h4>Low Stock Items</h4>
          <div class="widget-value" id="low-stock">Loading...</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="widget">
          <h4>Today’s Sales</h4>
          <div class="widget-value" id="todays-sales">Loading...</div>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="widget">
          <h4>Pending POs</h4>
          <div class="widget-value" id="pending-pos">Loading...</div>
        </div>
      </div>
    </div>

    <!-- Functional Cards -->
    <div class="row g-4 mt-2">
      <div class="col-md-4">
        <div class="card p-4 text-center">
          <i class="bi bi-box-seam-fill mb-2"></i>
          <h5 class="card-title">Inventory Monitoring</h5>
          <p>Monitor stock by category, location, or status</p>
          <a href="inventory_monitoring.php" class="btn btn-outline-success">View</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <i class="bi bi-tools mb-2"></i>
          <h5 class="card-title">Stock Operations</h5>
          <p>Receive goods, transfer stock, approve adjustments</p>
          <a href="stock_operations.php" class="btn btn-outline-success">Manage</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <i class="bi bi-cart-check-fill mb-2"></i>
          <h5 class="card-title">Purchasing Oversight</h5>
          <p>Submit purchase requests, track PO statuses</p>
          <a href="purchasing_oversight.php" class="btn btn-outline-success">Manage</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <i class="bi bi-cash-coin mb-2"></i>
          <h5 class="card-title">Sales & Returns</h5>
          <p>Monitor cashier sales, approve returns</p>
          <a href="sales_returns.php" class="btn btn-outline-success">View</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <i class="bi bi-bar-chart-line-fill mb-2"></i>
          <h5 class="card-title">Reports</h5>
          <p>Generate stock and sales reports</p>
          <a href="reports.php" class="btn btn-outline-success">View</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <i class="bi bi-bell-fill mb-2"></i>
          <h5 class="card-title">Notifications</h5>
          <p>Manage notification preferences</p>
          <a href="notifications.php" class="btn btn-outline-success">Manage</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="card-footer">
    &copy; 2025 GreenChoice Market - Stock Manager Dashboard
  </div>

  <!-- Dashboard Live Data Loader -->
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      fetch("dashboard_data.php")
        .then((res) => res.json())
        .then((data) => {
          document.getElementById("total-products").innerText = data.totalProducts;
          document.getElementById("low-stock").innerText = data.lowStock;
          document.getElementById("todays-sales").innerText = "Rs. " + data.todaysSales;
          document.getElementById("pending-pos").innerText = data.pendingPOs;
        })
        .catch((error) => {
          console.error("Error fetching dashboard data:", error);
        });
    });
  </script>

</body>
</html>
