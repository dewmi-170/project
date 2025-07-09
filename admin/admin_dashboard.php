<?php
session_start();
include('../db_connect.php');

// Session check & role validation
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Total Users
$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$total_users = $user_data['total_users'] ?? 0;

// Monthly Sales (current month)
$current_month = date('Y-m');
$sales_query = "SELECT SUM(total_price) AS monthly_sales FROM sales WHERE DATE_FORMAT(sale_date, '%Y-%m') = '$current_month'";
$sales_result = mysqli_query($conn, $sales_query);
$sales_data = mysqli_fetch_assoc($sales_result);
$monthly_sales = $sales_data['monthly_sales'] ?? 0;
$monthly_sales_formatted = 'Rs. ' . number_format($monthly_sales, 2);

// Pending Returns Count
$returns_query = "SELECT COUNT(*) AS pending_returns FROM returns WHERE status = 'pending'";
$returns_result = mysqli_query($conn, $returns_query);
$returns_data = mysqli_fetch_assoc($returns_result);
$pending_returns = $returns_data['pending_returns'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard | GreenChoice Market</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    :root {
      --green: #198754;
      --blue: #0d6efd;
      --orange: #fd7e14;
      --dark-bg: #2f4f4f;
      --hover-dark: #1e3932;
    }

    body {
      background-color: #f4f7f6;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      position: fixed;
      top: 0; left: 0;
      width: 240px;
      height: 100%;
      background-color: var(--dark-bg);
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
      background-color: var(--hover-dark);
      border-left: 4px solid #00c851;
      color: #00c851;
    }

    .main-content {
      margin-left: 250px;
      padding: 30px;
      min-height: 100vh;
    }

    .highlight-card {
      border-left: 6px solid var(--green);
      background-color: #e9f7ef;
      transition: transform 0.2s ease;
      border-radius: 5px;
    }

    .highlight-card:hover {
      transform: scale(1.03);
    }

    .highlight-card.blue {
      border-left-color: var(--blue);
      background-color: #e7f1ff;
    }

    .highlight-card.orange {
      border-left-color: var(--orange);
      background-color: #fff4e6;
    }

    .card-title {
      font-weight: 600;
      color: #343a40;
    }

    footer.card-footer {
      background-color: #f8f9fa;
      color: #6c757d;
      text-align: center;
      padding: 15px;
      margin-top: 50px;
      position: relative;
      bottom: 0;
      width: 100%;
      margin-left: -250px;
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        position: relative;
      }
      .main-content {
        margin-left: 0;
      }
      footer.card-footer {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-shop-window me-2"></i>GreenChoice Admin</h4>
  <a href="admin_dashboard.php" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="user_management.php"><i class="bi bi-person-gear me-2"></i> User Management</a>
  <a href="product_management.php"><i class="bi bi-box-seam me-2"></i> Product Management</a>
  <a href="inventory_oversight.php"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <h3>Welcome, Admin 👋</h3>

  <!-- Summary Cards -->
  <div class="row g-4 my-3">
    <div class="col-md-4">
      <div class="card p-3 highlight-card green">
        <div class="card-title">Total Users</div>
        <h4><?php echo $total_users; ?></h4>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 highlight-card blue">
        <div class="card-title">Monthly Sales</div>
        <h4><?php echo $monthly_sales_formatted; ?></h4>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-3 highlight-card orange">
        <div class="card-title">Pending Returns</div>
        <h4><?php echo $pending_returns; ?></h4>
      </div>
    </div>
  </div>

  <!-- Sales Chart -->
  <div class="card p-4 my-4">
    <h5>Sales Overview</h5>
    <canvas id="salesChart" height="100"></canvas>
  </div>

  <!-- Quick Links -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5><i class="bi bi-person-gear"></i> User Management</h5>
        <p>Create, edit, and manage users</p>
        <a href="user_management.php" class="btn btn-outline-success">Manage</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5><i class="bi bi-box-seam"></i> Product Management</h5>
        <p>Manage product listings</p>
        <a href="product_management.php" class="btn btn-outline-success">Manage</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5><i class="bi bi-graph-up-arrow"></i> Inventory Oversight</h5>
        <p>Check stock and adjustments</p>
        <a href="inventory_oversight.php" class="btn btn-outline-success">View</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5><i class="bi bi-currency-dollar"></i> Sales & Returns</h5>
        <p>Monitor sales and returns</p>
        <a href="sales_return.php" class="btn btn-outline-success">View</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5><i class="bi bi-bar-chart-fill"></i> Reports</h5>
        <p>Reports & Performance Analytics</p>
        <a href="reports_analytics.php" class="btn btn-outline-success">View</a>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card p-4 text-center">
        <h5><i class="bi bi-bell-fill"></i> Notifications</h5>
        <p>System alerts & reminders</p>
        <a href="notifications.php" class="btn btn-outline-success">Manage</a>
      </div>
    </div>
  </div>

 

<!-- Footer -->
<footer class="card-footer">
  &copy; 2025 GreenChoice Market. All rights reserved.
</footer>

<!-- Chart.js Script -->
<script>
  const ctx = document.getElementById('salesChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Sales (Rs.)',
        data: [1200, 1900, 3000, 2500, 3200, 4000], // Static example data; update with dynamic data if needed
        backgroundColor: 'rgba(25,135,84,0.2)',
        borderColor: 'rgba(25,135,84,1)',
        borderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
</body>
</html>
