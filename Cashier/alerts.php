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
  <title>Live Alerts | GreenChoice Cashier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 0;
      padding: 0;
    }

    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #256029;
      position: fixed;
      padding: 20px;
      color: white;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      padding: 12px 15px;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      margin-bottom: 10px;
      transition: 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #1b4b20;
    }

    .logout-btn {
      background-color: #c62828;
      padding: 10px;
      text-align: center;
      border-radius: 6px;
      color: white;
      text-decoration: none;
      margin-top: 30px;
      display: block;
    }

    .main {
      margin-left: 250px;
      padding: 30px;
    }

    .alert-title {
      font-size: 1.5rem;
      font-weight: bold;
    }

    .refresh-note {
      font-size: 0.9rem;
      color: gray;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4>GreenChoice Cashier</h4>
  <a href="cashier_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
  <a href="sales_billing.php"><i class="bi bi-credit-card me-2"></i>Sales & Billing</a>
  <a href="stock_view.php"><i class="bi bi-box-seam me-2"></i>Stock View</a>
  <a href="returns_refunds.php"><i class="bi bi-arrow-left-right me-2"></i>Returns & Refunds</a>
  <a href="sales_reports.php"><i class="bi bi-graph-up-arrow me-2"></i>Reports</a>
  <a href="alerts.php" class="active"><i class="bi bi-bell-fill me-2"></i>Alerts</a>
  <a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
</div>

<!-- Main Content -->
<div class="main">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="alert-title"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Low Stock Alerts</h2>
    <span class="refresh-note">Auto-refreshing every 10 seconds</span>
  </div>

  <div id="lowStockAlerts"></div>
</div>

<!-- Scripts -->
<script>
function fetchLowStockAlerts() {
  fetch('low_stock_alerts.php')
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('lowStockAlerts');
      container.innerHTML = '';

      if (data.length === 0) {
        container.innerHTML = '<div class="alert alert-success">✅ No low stock items.</div>';
      } else {
        data.forEach(item => {
          const div = document.createElement('div');
          div.className = 'alert alert-warning d-flex justify-content-between align-items-center';
          div.innerHTML = `
            <div>
              <strong>⚠️ ${item.product_name}</strong> stock is low! <br>
              Remaining Quantity: <span class="badge bg-danger">${item.stock_qty}</span>
            </div>
            <i class="bi bi-exclamation-circle fs-3 text-warning"></i>
          `;
          container.appendChild(div);
        });
      }
    });
}

setInterval(fetchLowStockAlerts, 10000); // Every 10 seconds
fetchLowStockAlerts(); // Initial load
</script>

</body>
</html>
