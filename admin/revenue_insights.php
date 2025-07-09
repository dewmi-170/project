<?php
session_start();
require_once('../config/db.php');

// Authorization check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch revenue and profit data (dummy data for example)
$labels = ['January', 'February', 'March', 'April', 'May'];
$revenueData = [50000, 60000, 55000, 65000, 70000];  // Monthly revenue
$profitData = [15000, 18000, 16000, 20000, 25000];    // Monthly profit

// Calculate profit margin percentage for each month
$profitMarginData = [];
foreach ($revenueData as $index => $revenue) {
    $profitMarginData[] = round(($profitData[$index] / $revenue) * 100, 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Revenue Insights | GreenChoice Market</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #e6f4ea;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            min-height: 100vh;
        }
        .container {
            margin-top: 30px;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 0px 15px rgba(0, 128, 0, 0.1);
            padding-bottom: 100px; /* Space for back button */
        }
        .section-title {
            color: #198754;
            font-weight: bold;
            margin-bottom: 20px;
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
        .btn-back {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border-radius: 30px;
            padding: 10px 20px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0,128,0,0.2);
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .btn-back:hover {
            background-color: #218838;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title">📊 Revenue Insights & Profit Margins</h2>
    </div>

    <!-- Summary Cards -->
    <div class="row text-center mb-4">
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5>Total Revenue</h5>
                <p> 3,000,000</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5>Total Profit</h5>
                <p>800,000</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5>Overall Profit Margin</h5>
                <p>26.7%</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card bg-light">
                <h5>Growth (This Month)</h5>
                <p>+20%</p>
            </div>
        </div>
    </div>

    <!-- Chart: Revenue vs Profit -->
    <div class="mb-5">
        <canvas id="revenueChart"></canvas>
    </div>

    <!-- Monthly Insights Table -->
    <h4 class="section-title">Monthly Revenue & Profit Margins</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-success">
            <tr>
                <th>#</th>
                <th>Month</th>
                <th>Revenue (Rs.)</th>
                <th>Profit (Rs.)</th>
                <th>Profit Margin (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($labels as $index => $label): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $label ?></td>
                    <td> <?= number_format($revenueData[$index]) ?></td>
                    <td> <?= number_format($profitData[$index]) ?></td>
                    <td><?= $profitMarginData[$index] ?>%</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Back Button -->
<a href="reports_analytics.php" class="btn-back">⬅️ Back</a>

<!-- Chart.js Script -->
<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [
            {
                label: 'Revenue (Rs.)',
                data: <?= json_encode($revenueData) ?>,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Profit (Rs.)',
                data: <?= json_encode($profitData) ?>,
                borderColor: 'green',
                backgroundColor: 'rgba(0, 255, 0, 0.1)',
                fill: true,
                tension: 0.3
            },
            {
                label: 'Profit Margin (%)',
                data: <?= json_encode($profitMarginData) ?>,
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
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Revenue and Profit Performance'
            }
        }
    }
});
</script>

</body>
</html>
