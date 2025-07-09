<?php
session_start();
require_once '../db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

// Fetch saved preferences
$sql = "SELECT * FROM notification_preferences WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$preferences = $result->fetch_assoc();

function isChecked($field) {
    global $preferences;
    return isset($preferences[$field]) && $preferences[$field] == 1 ? 'checked' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Notifications | GreenChoice Stock Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
    }
    .sidebar {
      position: fixed;
      top: 0; left: 0;
      width: 250px;
      height: 100vh;
      background-color: #343a40;
      color: #28a745;
      padding: 20px;
      display: flex;
      flex-direction: column;
      z-index: 1000;
    }
    .sidebar h4 {
      font-weight: bold;
      margin-bottom: 30px;
      color: #28a745;
    }
    .sidebar a {
      display: block;
      color: #ffffff;
      text-decoration: none;
      margin: 8px 0;
      padding: 10px 12px;
      border-radius: 6px;
      font-weight: 500;
      transition: background-color 0.3s ease;
    }
    .sidebar a:hover, .sidebar a.active {
      background-color: #28a745;
      color: white;
    }
    .container {
      margin-left: 270px;
      padding: 40px 30px;
      max-width: 900px;
    }
    .notification-card {
      background-color: white;
      border-radius: 15px;
      padding: 30px 30px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
      margin-bottom: 30px;
    }
    .btn-save {
      float: right;
    }
    h2 {
      font-weight: 700;
      color: #198754;
      margin-bottom: 40px;
      letter-spacing: 0.04em;
    }
    /* Toast Container position */
    #toastArea {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1100;
      max-width: 320px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam-fill"></i> Stock Manager</h4>
  <a href="stock_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="inventory_monitoring.php"><i class="bi bi-boxes"></i> Inventory Monitoring</a>
  <a href="stock_operations.php"><i class="bi bi-tools"></i> Stock Operations</a>
  <a href="purchasing_oversight.php"><i class="bi bi-cart-check"></i> Purchasing Oversight</a>
  <a href="sales_returns.php"><i class="bi bi-cash-coin"></i> Sales & Returns</a>
  <a href="reports.php"><i class="bi bi-bar-chart-line"></i> Reports</a>
  <a href="notifications.php" class="active"><i class="bi bi-bell-fill"></i> Notifications</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Log Out</a>
</div>

<!-- Toast Area -->
<div id="toastArea"></div>

<!-- Main Content -->
<div class="container">
  <h2>🔔 Manage Notifications</h2>

  <!-- Low Stock Alerts Section -->
  <div class="notification-card">
    <h5>📉 Low Stock Alerts</h5>
    <?php
    $threshold = 5;
    $lowStockSQL = "SELECT product_id, product_name, stock_qty FROM products WHERE stock_qty <= ? ORDER BY stock_qty ASC";
    $stmt = $conn->prepare($lowStockSQL);
    $stmt->bind_param("i", $threshold);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0): ?>
      <ul class="list-group mt-3">
        <?php while ($row = $result->fetch_assoc()): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <strong><?= htmlspecialchars($row['product_name']) ?></strong>
            <span class="badge bg-danger rounded-pill"><?= $row['stock_qty'] ?> left</span>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <div class="alert alert-success mt-3">✅ All stocks are at healthy levels.</div>
    <?php endif; 
    $stmt->close();
    ?>
  </div>

  <!-- Notification Preferences Section -->
  <div class="notification-card mt-4">
    <h5>Notification Preferences</h5>
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Notification preferences saved successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <form action="update_notifications.php" method="POST" class="mt-3">
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="stock_low" id="stockLow" <?= isChecked('stock_low') ?>>
        <label class="form-check-label" for="stockLow">Notify when stock is low</label>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="sales_summary" id="salesSummary" <?= isChecked('sales_summary') ?>>
        <label class="form-check-label" for="salesSummary">Daily sales summary notifications</label>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="purchase_requests" id="purchaseRequests" <?= isChecked('purchase_requests') ?>>
        <label class="form-check-label" for="purchaseRequests">Notifications for purchase request updates</label>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="return_logs" id="returnLogs" <?= isChecked('return_logs') ?>>
        <label class="form-check-label" for="returnLogs">Notifications for return logs</label>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="stock_movement" id="stockMovement" <?= isChecked('stock_movement') ?>>
        <label class="form-check-label" for="stockMovement">Notifications for stock movement</label>
      </div>
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="purchase_approvals" id="purchaseApprovals" <?= isChecked('purchase_approvals') ?>>
        <label class="form-check-label" for="purchaseApprovals">Notify when purchase requests are approved</label>
      </div>
      <div class="mt-4">
        <a href="stock_dashboard.php" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-success btn-save">Save Preferences</button>
      </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Toast Notification Script -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    fetch('live_low_stock.php')
      .then(response => response.json())
      .then(data => {
        data.forEach(msg => showToast(msg));
      })
      .catch(e => console.error('Error fetching low stock alerts:', e));
  });

  function showToast(message) {
    const toastArea = document.getElementById('toastArea');

    const toastEl = document.createElement('div');
    toastEl.className = 'toast align-items-center text-white bg-danger border-0';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');

    toastEl.innerHTML = `
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    `;

    toastArea.appendChild(toastEl);

    const toast = new bootstrap.Toast(toastEl, { delay: 8000 });
    toast.show();
  }
</script>

</body>
</html>
