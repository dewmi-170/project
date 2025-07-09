<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get all products
$productQuery = $conn->query("
    SELECT product_id, product_name, stock_qty
    FROM products
    ORDER BY product_name ASC
");


$products = [];
if ($productQuery) {
    $products = $productQuery->fetch_all(MYSQLI_ASSOC);
}

// Dummy monthly data - in production, calculate from stock_in and stock_out tables
$labels = ['January', 'February', 'March', 'April', 'May'];
$stockInData = [200, 150, 300, 250, 100];
$stockOutData = [150, 100, 200, 180, 90];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Trends | GreenChoice Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #e6f4ea;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            margin-top: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0, 128, 0, 0.1);
            position: relative;
        }
        .section-title {
            color: #198754;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-back {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            transition: 0.3s;
            position: absolute;
            bottom: 20px;
            right: 30px;
        }
        .btn-back:hover {
            background-color: #218838;
        }
        .summary-card {
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,128,0,0.1);
            padding: 15px;
            text-align: center;
        }
        .summary-card h5 {
            font-weight: bold;
            color: #198754;
        }
        .low-stock {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="section-title"><i class="fas fa-chart-line"></i> Inventory Trends & Stock Movement</h2>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="🔍 Search Product">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control">
        </div>
        <div class="col-md-3">
            <input type="date" class="form-control">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" id="printBtn"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>

    <!-- Summary -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5><i class="fas fa-boxes-stacked"></i> Opening Stock</h5>
                <p>1,200 Units</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5><i class="fas fa-arrow-down"></i> Stock In</h5>
                <p>300 Units</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5><i class="fas fa-arrow-up"></i> Stock Out</h5>
                <p>250 Units</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5><i class="fas fa-warehouse"></i> Closing Stock</h5>
                <p>1,250 Units</p>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="mb-5">
        <canvas id="inventoryChart" height="100"></canvas>
    </div>

    <!-- Top Selling Products -->
    <div class="mb-5">
        <h4 class="section-title"><i class="fas fa-star"></i> Top Selling Products</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Quantity Sold</th>
                    <th>Total Sales (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Milo 400g</td>
                    <td>120</td>
                    <td>45,000</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Sunlight 100g</td>
                    <td>80</td>
                    <td>20,000</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Low Stock -->
    <div class="mb-5">
        <h4 class="section-title"><i class="fas fa-triangle-exclamation"></i> Low Stock Products</h4>
        <table class="table table-bordered table-hover">
            <thead class="table-warning">
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Remaining Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($products as $product): ?>
                    <?php if ($product['stock_qty'] <= 10): ?>
                        <tr class="low-stock">
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($product['product_name']) ?></td>
                            <td><?= $product['stock_qty'] ?></td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($i == 1): ?>
                    <tr><td colspan="3" class="text-center">No Low Stock Items</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Back -->
    <a href="reports_analytics.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<!-- Chart Script -->
<script>
const ctx = document.getElementById('inventoryChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Stock In',
                data: <?= json_encode($stockInData) ?>,
                borderColor: 'green',
                backgroundColor: 'rgba(0, 128, 0, 0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Stock Out',
                data: <?= json_encode($stockOutData) ?>,
                borderColor: 'red',
                backgroundColor: 'rgba(255, 0, 0, 0.1)',
                fill: true,
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' },
            title: { display: true, text: 'Monthly Stock In vs Stock Out' }
        }
    }
});

// Print
document.getElementById('printBtn').addEventListener('click', function () {
    window.print();
});
</script>

</body>
</html>
