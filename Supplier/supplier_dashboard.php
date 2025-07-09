<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Supplier Dashboard | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #eef6f1; font-family: 'Segoe UI', sans-serif; }
    .sidebar {
      width: 250px; height: 100vh; background-color: #2e7d32; position: fixed;
      top: 0; left: 0; padding: 30px 20px; color: white;
    }
    .sidebar h4 { font-size: 22px; margin-bottom: 30px; text-align: center; }
    .sidebar a {
      display: block; padding: 12px 10px; color: white; text-decoration: none;
      margin-bottom: 10px; border-radius: 8px; transition: background-color 0.3s ease;
    }
    .sidebar a.active {
  background-color: #1b5e20;
  font-weight: bold;
}

    .sidebar a:hover { background-color: #1b5e20; }
    
    .main-content {
      margin-left: 270px;
      padding: 20px;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .btn-logout {
      background-color: #c62828;
      color: white;
    }
    .card {
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      border: none;
    }
  </style>
</head>
<body>
 <!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php"><i class="bi bi-bag-check"></i> View Orders</a>
  <a href="manage_products.php"><i class="bi bi-tags"></i> Manage Products & Prices</a>
  <a href="shipping_documents.php"><i class="bi bi-truck"></i> Shipping & Documents</a>
  <a href="supplier_communication.php"><i class="bi bi-chat-dots"></i> Communication</a>
  <a href="supplier_invoices.php"><i class="bi bi-receipt"></i> Invoice Tracking</a>
  <a href="supplier_notifications.php"><i class="bi bi-bell"></i> Notifications</a>
  <hr>
  <a href="../logout.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>


  <!-- Main Content -->
  <div class="main-content">
    <div class="topbar">
      <h3>Welcome, Supplier</h3>
      <div>
        <span class="notification me-3"><i class="bi bi-bell-fill"></i></span>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5 class="card-title">📋 View Orders</h5>
          <p>Review and manage purchase orders</p>
          <a href="view_orders.php" class="btn btn-outline-success">View</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5 class="card-title">🛍️ Manage Products</h5>
          <p>Update prices, availability, lead times</p>
          <a href="manage_products.php" class="btn btn-outline-success">Manage</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5 class="card-title">📄 Shipping Documents</h5>
          <p>Upload shipping info, invoices</p>
          <a href="shipping_documents.php" class="btn btn-outline-success">Upload</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5 class="card-title">💬 Communication</h5>
          <p>Message Admin or Stock Manager</p>
          <a href="supplier_communication.php" class="btn btn-outline-success">Open</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5 class="card-title">🧾 Invoices</h5>
          <p>View and track payment statuses</p>
          <a href="supplier_invoices.php" class="btn btn-outline-success">Track</a>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-4 text-center">
          <h5 class="card-title">🔔 Notifications</h5>
          <p>Manage alerts and updates</p>
          <a href="supplier_notifications.php" class="btn btn-outline-success">Manage</a>
        </div>
      </div>
    </div>
  </div>
 
  <div class="text-center mt-4">
    &copy; 2025 GreenChoice Market - Supplier Panel
  </div>
</body>
</html>
