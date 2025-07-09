<?php
session_start();
// Connect to DB
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch Sales Data
$sales_sql = "SELECT * FROM sales ORDER BY sale_date DESC LIMIT 50";
$sales_result = $conn->query($sales_sql);

// Fetch Return Requests
$returns_sql = "SELECT * FROM returns ORDER BY return_id DESC LIMIT 50";
$returns_result = $conn->query($returns_sql);

// Calculate total sales
$total_sales = 0;
if ($sales_result->num_rows > 0) {
    // Reset pointer to start
    $sales_result->data_seek(0);
    while ($row = $sales_result->fetch_assoc()) {
        $total_sales += $row['total_price'];
    }
    // Reset pointer again for later display
    $sales_result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sales & Returns | GreenChoice Stock Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 250px;
      background-color: #343a40;
      padding: 20px;
      color: #28a745;
      flex-shrink: 0;
    }
    .sidebar h4 {
      margin-bottom: 30px;
      font-weight: bold;
    }
    .sidebar a {
      display: block;
      padding: 10px;
      color: #ccc;
      text-decoration: none;
      margin-bottom: 5px;
      border-radius: 5px;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #28a745;
      color: white;
    }
    .main-content {
      flex-grow: 1;
      padding: 40px;
      position: relative;
    }
    .section {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 25px;
      margin-bottom: 30px;
    }
    h2.text-success {
      margin-bottom: 30px;
    }
    .badge {
      font-size: 0.9rem;
    }
    .btn-back {
      position: absolute;
      bottom: 20px;
      right: 40px;
    }
    .total-footer {
      font-weight: bold;
      background-color: #e6ffe6;
      color: #1b5e20;
    }
  </style>
</head>
<body>

<div class="sidebar">
  <h4><i class="bi bi-box-seam-fill"></i> Stock Manager</h4>
  <a href="stock_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="inventory_monitoring.php"><i class="bi bi-boxes"></i> Inventory Monitoring</a>
  <a href="stock_operations.php"><i class="bi bi-tools"></i> Stock Operations</a>
  <a href="purchasing_oversight.php"><i class="bi bi-cart-check"></i> Purchasing Oversight</a>
  <a href="sales_returns.php" class="active"><i class="bi bi-cash-coin"></i> Sales & Returns</a>
  <a href="reports.php"><i class="bi bi-bar-chart-line"></i> Reports</a>
  <a href="notifications.php"><i class="bi bi-bell-fill"></i> Notifications</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Log Out</a>
</div>

<div class="main-content">
  <h2 class="text-success">💸 Sales & Returns</h2>

  <!-- Sales Section -->
  <div class="section">
    <h3 class="section-title">🧾 Cashier Sales</h3>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Invoice ID</th>
          <th>Cashier</th>
          <th>Date</th>
          <th>Total Amount</th>
          <th>Payment Method</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($sales_result->num_rows > 0): ?>
          <?php while ($row = $sales_result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['invoice_id']) ?></td>
              <td><?= htmlspecialchars($row['cashier_name']) ?></td>
              <td><?= htmlspecialchars($row['sale_date']) ?></td>
              <td>Rs. <?= number_format($row['total_price'], 2) ?></td>
              <td><?= htmlspecialchars($row['payment_method']) ?></td>
            </tr>
          <?php endwhile; ?>
          <tr class="total-footer">
            <td colspan="3" class="text-end">Total Sales:</td>
            <td colspan="2">Rs. <?= number_format($total_sales, 2) ?></td>
          </tr>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No sales records found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Returns Section -->
  <div class="section">
    <h3 class="section-title">↩️ Return Requests</h3>
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Return ID</th>
          <th>Invoice</th>
          <th>Reason</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($returns_result->num_rows > 0): ?>
          <?php while ($row = $returns_result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['return_id']) ?></td>
              <td><?= htmlspecialchars($row['invoice_id']) ?></td>
              <td><?= htmlspecialchars($row['reason']) ?></td>
              <td>
                <?php
                  $status = $row['status'];
                  echo $status == 'Pending' ? "<span class='badge bg-warning text-dark'>Pending</span>" :
                       ($status == 'Approved' ? "<span class='badge bg-success'>Approved</span>" :
                       "<span class='badge bg-danger'>Rejected</span>");
                ?>
              </td>
              <td>
                <?php if ($status == 'Pending'): ?>
                  <form method="post" action="update_return_status.php" style="display:inline-block;">
                    <input type="hidden" name="return_id" value="<?= htmlspecialchars($row['return_id']) ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                  </form>
                  <form method="post" action="update_return_status.php" style="display:inline-block;">
                    <input type="hidden" name="return_id" value="<?= htmlspecialchars($row['return_id']) ?>">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                  </form>
                <?php else: ?> -
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No return requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Back Button (Bottom Right) -->
  <button onclick="location.href='stock_dashboard.php'" class="btn btn-outline-secondary btn-back">
    <i class="bi bi-arrow-left"></i> Back to Dashboard
  </button>
</div>

</body>
</html>
