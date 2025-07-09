<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales & Returns | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7f6;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      height: 100vh;
      width: 240px;
      position: fixed;
      top: 0;
      left: 0;
      background-color:   #2f4f4f;
      padding-top: 20px;
      color: white;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 700;
    }

    .sidebar a {
      color: white;
      padding: 12px 20px;
      display: block;
      text-decoration: none;
      font-weight: 500;
      transition: background 0.3s;
    }

    .sidebar a:hover, .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }

    .container {
      margin-left: 230px;
      margin-top: 40px;
    }

    .section-title {
      margin-bottom: 30px;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease-in-out;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-title {
      font-size: 18px;
      font-weight: 600;
    }

    .btn-sales, .btn-returns {
      width: 100%;
      padding: 12px;
      font-size: 16px;
    }

    .btn-sales {
      background-color: #28a745;
      color: white;
    }

    .btn-returns {
      background-color: #ffc107;
      color: black;
    }

    .back-btn-fixed {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 999;
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      border-radius: 50px;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: background-color 0.3s ease;
    }

    .back-btn i {
      margin-right: 8px;
      font-size: 20px;
    }

    .back-btn:hover {
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
  <a href="sales_return.php" class="active"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>
  <!-- Main Content -->
  <div class="container">
    <div class="d-flex justify-content-between align-items-center section-title">
      <h2>Sales & Returns</h2>
      <a href="sales_report.php" class="btn btn-outline-success"><i class="bi bi-graph-up-arrow"></i> View Reports</a>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="card p-4 text-center">
          <h5 class="card-title"><i class="bi bi-cash-stack"></i> Process Sale</h5>
          <p>Handle new sales and generate invoices.</p>
          <a href="process_sale.php" class="btn btn-sales">Go to Sales</a>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card p-4 text-center">
          <h5 class="card-title"><i class="bi bi-arrow-counterclockwise"></i> Process Return</h5>
          <p>Manage product returns and issue refunds.</p>
          <a href="process_return.php" class="btn btn-returns">Go to Returns</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Fixed Back Button -->
  <div class="back-btn-fixed">
    <a href="admin_dashboard.php" class="back-btn">
      <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
    </a>
  </div>

</body>
</html>
