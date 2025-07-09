<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'cashier')) {
    header("Location: ../login.php");
    exit;
}

// Fetch Sales with Product Name
$sales = [];
$salesQuery = $conn->query("
    SELECT sales.*, products.product_name AS product_name
    FROM sales
    JOIN products ON sales.product_id = products.product_id
    ORDER BY sales.sale_date DESC
");

if ($salesQuery) {
    $sales = $salesQuery->fetch_all(MYSQLI_ASSOC);
}

// Fetch Returns with Product Name
$returns = [];
$returnsQuery = $conn->query("
    SELECT returns.*, products.product_name AS product_name
    FROM returns
    JOIN products ON returns.product_id = products.product_id
    ORDER BY returns.return_date DESC
");



if ($returnsQuery) {
    $returns = $returnsQuery->fetch_all(MYSQLI_ASSOC);
}
?>

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
            background-color: #2f4f4f;
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

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .section-title {
            margin-bottom: 30px;
            color: #198754;
            font-weight: bold;
        }

        .table thead {
            background-color: #198754;
            color: white;
        }

        .table-returns thead {
            background-color: #ffc107;
            color: black;
        }

        .controls {
            margin-bottom: 20px;
        }

        .btn-print {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            border-radius: 8px;
        }

        .btn-print:hover {
            background-color: #0b5ed7;
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
  <a href="inventory_oversight.php" class="active"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">📊 Sales & Returns Report</h2>
        <button class="btn btn-print" onclick="printReport()">🖨️ Print Report</button>
    </div>

    <!-- Filters -->
    <div class="row controls mb-4">
        <div class="col-md-4 mb-2">
            <input type="text" id="searchInput" class="form-control" placeholder="🔎 Search by Product Name / User">
        </div>
        <div class="col-md-3 mb-2">
            <input type="date" id="fromDate" class="form-control">
        </div>
        <div class="col-md-3 mb-2">
            <input type="date" id="toDate" class="form-control">
        </div>
    </div>

    <!-- Report Content -->
    <div id="reportContent">
       <!-- Sales Table -->
<div class="mb-5">
    <h4>🛒 Sales</h4>
    <table class="table table-bordered table-hover" id="salesTable">
        <thead>
        <tr>
            <th>Sale ID</th>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Sale Price (Rs)</th>
            <th>Total Price (Rs)</th>
            <th>Sale Date</th>
            <th>Total Amount (Rs)</th> <!-- ✅ New Column -->
        </tr>
        </thead>
        <tbody>
        <?php 
        $grand_total = 0;
        if (!empty($sales)): ?>
            <?php foreach ($sales as $sale): 
                $grand_total += $sale['total_price'];
            ?>
                <tr>
                    <td><?= $sale['sale_id'] ?></td>
                    <td><?= $sale['product_id'] ?></td>
                    <td><?= htmlspecialchars($sale['product_name']) ?></td>
                    <td><?= $sale['quantity'] ?></td>
                    <td><?= number_format($sale['price'], 2) ?></td>
                    <td><?= number_format($sale['total_price'], 2) ?></td>
                    <td><?= $sale['sale_date'] ?></td>
                    <td><?= number_format($sale['total_price'], 2) ?></td> <!-- ✅ Repeat Total Price -->
                </tr>
            <?php endforeach; ?>
            <!-- ✅ Grand Total Row -->
            <tr style="font-weight: bold; background-color: #d4edda;">
                <td colspan="7" class="text-end">🧮 Grand Total Amount (Rs):</td>
                <td><?= number_format($grand_total, 2) ?></td>
            </tr>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">No Sales Recorded</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

        <!-- Returns Table -->
        <div class="mb-5">
            <h4>🔄 Returns</h4>
            <table class="table table-bordered table-hover table-returns" id="returnsTable">
                <thead>
                <tr>
                    <th>Return ID</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Reason</th>
                    <th>Returned By</th>
                    <th>Return Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($returns)): ?>
                    <?php foreach ($returns as $return): ?>
                        <tr>
                            <td><?= $return['return_id'] ?></td>
                            <td><?= $return['product_id'] ?></td>
                            <td><?= htmlspecialchars($return['product_name']) ?></td>
                            <td><?= $return['quantity'] ?></td>
                            <td><?= htmlspecialchars($return['reason']) ?></td>
                            <td><?= htmlspecialchars($return['returned_by']) ?></td>
                            <td><?= $return['return_date'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">No Returns Recorded</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JS -->
<script>
function printReport() {
    var content = document.getElementById('reportContent').innerHTML;
    var win = window.open('', '', 'height=800,width=1200');
    win.document.write('<html><head><title>Print Report</title>');
    win.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    win.document.write('</head><body>');
    win.document.write(content);
    win.document.write('</body></html>');
    win.document.close();
    win.print();
}

const searchInput = document.getElementById('searchInput');
const fromDate = document.getElementById('fromDate');
const toDate = document.getElementById('toDate');

searchInput.addEventListener('input', filterTables);
fromDate.addEventListener('change', filterTables);
toDate.addEventListener('change', filterTables);

function filterTables() {
    const searchValue = searchInput.value.toLowerCase();
    const from = fromDate.value;
    const to = toDate.value;

    filterTable('salesTable', 2, 6, 7, searchValue, from, to);
    filterTable('returnsTable', 2, 5, 6, searchValue, from, to);
}

function filterTable(tableId, productCol, userCol, dateCol, search, fromDate, toDate) {
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let row of rows) {
        const cells = row.getElementsByTagName('td');
        if (cells.length === 0) continue;

        const product = cells[productCol].innerText.toLowerCase();
        const user = cells[userCol].innerText.toLowerCase();
        const date = cells[dateCol].innerText;

        let matchesSearch = !search || product.includes(search) || user.includes(search);
        let matchesDate = true;

        if (fromDate) matchesDate = matchesDate && (date >= fromDate);
        if (toDate) matchesDate = matchesDate && (date <= toDate);

        row.style.display = (matchesSearch && matchesDate) ? '' : 'none';
    }
}
</script>

</body>
</html>
