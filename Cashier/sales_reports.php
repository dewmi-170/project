<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    header("Location: ../login.php");
    exit;
}

include 'db_connection.php';
$conn = OpenCon();

$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

$query = "SELECT invoice_id, cashier_name, 
         IFNULL(total_price, 0) AS total_price, 
         IFNULL(status, 'Completed') AS status, 
         sale_date 
         FROM sales";

if ($startDate && $endDate) {
    $query .= " WHERE sale_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $startDate, $endDate);
} else {
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();

$sales = [];
$totalSales = 0;
$totalTransactions = 0;
$totalRefunds = 0;

while ($row = $result->fetch_assoc()) {
    $sales[] = $row;
    $totalTransactions++;
    if (isset($row['status']) && $row['status'] === 'Refunded') {
        $totalRefunds += (float) $row['total_price'];
    } else {
        $totalSales += (float) $row['total_price'];
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sales Reports | GreenChoice Market</title>
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
      padding: 10px;
      background-color: #d32f2f;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      text-align: center;
    }

    .logout-btn:hover {
      background-color: #b71c1c;
    }

    .main-content {
      flex: 1;
      padding: 30px;
      margin-left: 250px;
      overflow-y: auto;
      height: 100vh;
    }

    .btn-success {
      background-color: #2e7d32;
    }

    .btn-success:hover {
      background-color: #1b5e20;
    }

    table th {
      background-color: #2e7d32;
      color: white;
    }

    .back-btn {
      float: right;
      margin-bottom: 15px;
    }

    .print-only {
      display: none;
    }

    @media print {
      body {
        background: white;
        color: black;
      }

      .sidebar,
      .back-btn,
      .btn,
      .card-title,
      form,
      .logout-btn,
      .nav-links,
      .print-hide {
        display: none !important;
      }

      .main-content {
        margin: 0 !important;
        padding: 0 !important;
      }

      .print-only {
        display: block !important;
        text-align: center;
        font-size: 18px;
        margin-bottom: 20px;
      }

      table {
        width: 100%;
        font-size: 12pt;
        border-collapse: collapse;
      }

      table th, table td {
        border: 1px solid #000;
        padding: 6px;
      }
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <h4>GreenChoice Cashier</h4>
      <div class="nav-links">
        <a href="cashier_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="sales_billing.php"><i class="bi bi-credit-card-2-front"></i> Sales & Billing</a>
        <a href="stock_view.php"><i class="bi bi-search"></i> Stock View</a>
        <a href="returns_refunds.php"><i class="bi bi-arrow-return-left"></i> Returns & Refunds</a>
        <a href="sales_reports.php" class="active"><i class="bi bi-file-earmark-text"></i> Reports</a>
        <a href="alerts.php"><i class="bi bi-bell-fill"></i> Alerts</a>
      </div>
    </div>
    <a href="../logout.php" class="logout-btn d-flex align-items-center justify-content-center">
      <i class="bi bi-box-arrow-right me-2"></i> Log Out
    </a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <a href="cashier_dashboard.php" class="btn btn-outline-success back-btn print-hide">
      <i class="bi bi-arrow-left-circle"></i> Back
    </a>

    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mb-0">📋 Sales Reports</h2>
      <button onclick="window.print()" class="btn btn-outline-secondary print-hide">
        <i class="bi bi-printer-fill"></i> Print Report
      </button>
    </div>

    <p class="print-only"><?= date('Y-m-d') ?> — Printed by: <?= $_SESSION['username'] ?? 'Cashier' ?></p>

    <!-- Filter Form -->
    <div class="card mb-4 mx-auto print-hide" style="max-width: 800px;">
      <div class="card-body">
        <form class="row g-3 align-items-end" method="get" action="">
          <div class="col-md-5">
            <label for="startDate" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="startDate" name="startDate" value="<?= htmlspecialchars($startDate) ?>">
          </div>
          <div class="col-md-5">
            <label for="endDate" class="form-label">End Date</label>
            <input type="date" class="form-control" id="endDate" name="endDate" value="<?= htmlspecialchars($endDate) ?>">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4 text-center">
      <div class="col-md-4">
        <div class="card p-3">
          <h5>Total Sales</h5>
          <h3 class="text-success">Rs. <?= number_format($totalSales, 2) ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <h5>Total Transactions</h5>
          <h3><?= $totalTransactions ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-3">
          <h5>Total Refunds</h5>
          <h3 class="text-danger">Rs. <?= number_format($totalRefunds, 2) ?></h3>
        </div>
      </div>
    </div>

    <!-- Detailed Table -->
    <div class="card mx-auto" style="max-width: 1000px;">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-table"></i> Sales Details</h5>
        <table class="table table-bordered table-striped mt-3">
          <thead>
            <tr>
              <th>#</th>
              <th>Invoice ID</th>
              <th>Date</th>
              <th>Cashier</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($sales as $index => $sale): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($sale['invoice_id']) ?></td>
                <td><?= htmlspecialchars($sale['sale_date']) ?></td>
                <td><?= htmlspecialchars($sale['cashier_name']) ?></td>
                <td>Rs. <?= number_format($sale['total_price'], 2) ?></td>
                <td>
                  <span class="badge <?= $sale['status'] === 'Refunded' ? 'bg-danger' : 'bg-success' ?>">
                    <?= htmlspecialchars($sale['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($sales)): ?>
              <tr>
                <td colspan="6" class="text-center">No sales records found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
