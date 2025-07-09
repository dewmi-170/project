<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch purchase orders
$sql = "SELECT * FROM purchase_orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Orders | GreenChoice Supplier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #eef6f1;
      font-family: 'Segoe UI', sans-serif;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #2e7d32;
      position: fixed;
      top: 0;
      left: 0;
      padding: 30px 20px;
      color: white;
    }
    .sidebar h4 {
      font-size: 22px;
      margin-bottom: 30px;
      text-align: center;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 10px;
      color: white;
      text-decoration: none;
      margin-bottom: 10px;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    .sidebar a.active {
      background-color: #1b5e20;
      font-weight: bold;
    }
    .sidebar a:hover {
      background-color: #1b5e20;
    }
    .container {
      margin-left: 270px;
      margin-top: 40px;
      width: calc(100% - 270px);
    }
    .orders-table {
      background-color: white;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 25px;
    }
    .orders-table h5 {
      margin-bottom: 20px;
    }
    .btn-action {
      margin-right: 10px;
    }
    .table thead {
      background-color: #c8e6c9;
    }
    .btn-info i,
    .btn-success i,
    .btn-danger i {
      margin-right: 5px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php" class="active"><i class="bi bi-bag-check-fill"></i> View Orders</a>
  <a href="manage_products.php"><i class="bi bi-tags-fill"></i> Manage Products & Prices</a>
  <a href="shipping_documents.php"><i class="bi bi-truck"></i> Shipping & Documents</a>
  <a href="supplier_communication.php"><i class="bi bi-chat-dots-fill"></i> Communication</a>
  <a href="supplier_invoices.php"><i class="bi bi-receipt-cutoff"></i> Invoice Tracking</a>
  <a href="supplier_notifications.php"><i class="bi bi-bell-fill"></i> Notifications</a>
  <hr>
  <a href="../logout.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Orders Table -->
<div class="container">
  <h2 class="text-success mb-4"><i class="bi bi-card-list"></i> View Orders</h2>
  <div class="orders-table">
    <h5><i class="bi bi-journal-check"></i> Purchase Orders List</h5>
    <table class="table table-bordered table-hover align-middle">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Supplier</th>
          <th>Order Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['order_id']) ?></td>
              <td><?= htmlspecialchars($row['supplier_name']) ?></td>
              <td><?= htmlspecialchars($row['order_date']) ?></td>
              <td>
                <?php if ($row['status'] == 'Pending'): ?>
                  <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending</span>
                <?php elseif ($row['status'] == 'Approved'): ?>
                  <span class="badge bg-success"><i class="bi bi-check2-circle"></i> Approved</span>
                <?php elseif ($row['status'] == 'Canceled'): ?>
                  <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Canceled</span>
                <?php else: ?>
                  <?= htmlspecialchars($row['status']) ?>
                <?php endif; ?>
              </td>
              <td>
                <a href="view_order_details.php?order_id=<?= $row['order_id'] ?>" class="btn btn-info btn-sm">
                  <i class="bi bi-eye-fill"></i> View
                </a>
                <?php if ($row['status'] == 'Pending'): ?>
                  <a href="approve_order.php?order_id=<?= $row['order_id'] ?>" class="btn btn-success btn-sm">
                    <i class="bi bi-check-lg"></i> Approve
                  </a>
                  <a href="cancel_order.php?order_id=<?= $row['order_id'] ?>" class="btn btn-danger btn-sm">
                    <i class="bi bi-x-lg"></i> Cancel
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center text-muted">No orders found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
