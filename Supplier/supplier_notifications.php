<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}

// Sample notifications - Replace with database records if needed
$notifications = [
    ['id'=>1, 'icon'=>'bi-truck', 'title'=>'New Order Placed', 'message'=>'You have a new order to fulfill. Please check your order dashboard.', 'time'=>'5 minutes ago', 'unread'=>true],
    ['id'=>2, 'icon'=>'bi-cash-stack', 'title'=>'Invoice Paid', 'message'=>'Payment received for Invoice #INV-1021.', 'time'=>'Today at 9:15 AM', 'unread'=>false],
    ['id'=>3, 'icon'=>'bi-info-circle', 'title'=>'System Maintenance', 'message'=>'The platform will be under maintenance on June 20, 10 PM - 12 AM.', 'time'=>'2 days ago', 'unread'=>false],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Supplier Notifications | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f1fdf3;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      margin: 0;
      min-height: 100vh;
      color: #333;
    }
    .sidebar {
      width: 250px;
      background-color: #2e7d32;
      color: #fff;
      padding: 20px;
      position: fixed;
      height: 100vh;
      display: flex;
      flex-direction: column;
    }
    .sidebar h4 {
      font-weight: bold;
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 15px;
      color: #fff;
      text-decoration: none;
      border-radius: 8px;
      margin-bottom: 10px;
      gap: 12px;
      transition: background 0.3s;
    }
    .sidebar a.active,
    .sidebar a:hover {
      background-color: #1b5e20;
    }
    .btn-logout {
      margin-top: auto;
      background-color: #c62828;
      border: none;
      color: white;
      padding: 12px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
    }
    .btn-logout:hover {
      background-color: #b71c1c;
    }

    .container-main {
      margin-left: 270px;
      padding: 40px;
      flex-grow: 1;
      max-width: 900px;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .card-header {
      background-color: #2e7d32;
      color: #fff;
      padding: 16px 25px;
      font-size: 1.4rem;
      font-weight: 600;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn-back {
      background-color: #1b5e20;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 0.9rem;
    }

    .btn-back:hover {
      background-color: #145214;
    }

    .card-body {
      padding: 25px 30px;
    }

    .notification-item {
      display: flex;
      align-items: flex-start;
      gap: 16px;
      padding: 18px 15px;
      border-bottom: 1px solid #e0e0e0;
      border-left: 5px solid transparent;
    }

    .notification-item.unread {
      background-color: #e8f5e9;
      border-left-color: #2e7d32;
    }

    .notification-item i {
      font-size: 1.5rem;
      color: #2e7d32;
      margin-top: 5px;
    }

    .notification-text strong {
      font-size: 1.1rem;
      color: #1b5e20;
    }

    .notification-message {
      margin-top: 5px;
      font-size: 0.95rem;
    }

    .notification-time {
      font-size: 0.8rem;
      color: #888;
      margin-top: 8px;
    }

    .notification-empty {
      text-align: center;
      padding: 50px 0;
      color: #777;
    }

    .card-footer-order {
      margin-top: 30px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php"><i class="bi bi-bag-check"></i> View Orders</a>
  <a href="manage_products.php"><i class="bi bi-tags"></i> Manage Products</a>
  <a href="shipping_documents.php"><i class="bi bi-truck"></i> Shipping</a>
  <a href="supplier_communication.php"><i class="bi bi-chat-dots"></i> Communication</a>
  <a href="supplier_invoices.php"><i class="bi bi-receipt"></i> Invoices</a>
  <a href="supplier_notifications.php" class="active"><i class="bi bi-bell"></i> Notifications</a>
  <hr>
  <a href="../logout.php" class="btn-logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="container-main">
  <div class="card">
    <div class="card-header">
      <span><i class="bi bi-bell-fill"></i> Notifications</span>
      <a href="supplier_dashboard.php" class="btn-back"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <?php if (count($notifications) === 0): ?>
        <div class="notification-empty">
          <i class="bi bi-bell-slash fs-1"></i>
          <p>No notifications at the moment.</p>
        </div>
      <?php else: ?>
        <?php foreach ($notifications as $note): ?>
          <div class="notification-item <?= $note['unread'] ? 'unread' : '' ?>">
            <i class="bi <?= htmlspecialchars($note['icon']) ?>"></i>
            <div class="notification-text">
              <strong><?= htmlspecialchars($note['title']) ?></strong>
              <div class="notification-message"><?= htmlspecialchars($note['message']) ?></div>
              <div class="notification-time"><?= htmlspecialchars($note['time']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Approved Orders CTA -->
  <div class="card-footer-order">
    <div class="card shadow mt-4">
      <div class="card-body">
        <h5 class="card-title">📦 Approved Orders</h5>
        <p class="card-text">View orders approved by Admin for fulfillment.</p>
        <a href="supplier_orders.php" class="btn btn-success">View Orders</a>
      </div>
    </div>
  </div>
</div>
</body>
</html>
