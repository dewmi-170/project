<?php
// dashboard_data.php

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'stock_manager') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require '../db_connect.php'; // Make sure this path is correct

$response = [
    'totalProducts' => 0,
    'lowStock' => 0,
    'todaysSales' => 0,
    'pendingPOs' => 0
];

// Total products count
$totalQuery = "SELECT COUNT(*) AS total FROM products";
$totalResult = mysqli_query($conn, $totalQuery);
if ($totalResult) {
    $response['totalProducts'] = mysqli_fetch_assoc($totalResult)['total'];
}

// Low stock (below threshold, e.g., 10)
$lowStockQuery = "SELECT COUNT(*) AS low FROM products WHERE stock_qty < 10";
$lowStockResult = mysqli_query($conn, $lowStockQuery);
if ($lowStockResult) {
    $response['lowStock'] = mysqli_fetch_assoc($lowStockResult)['low'];
}

// Today's sales total (sum of total_price from today's sales)
$todaysDate = date('Y-m-d');
$salesQuery = "SELECT SUM(total_price) AS total FROM sales WHERE DATE(sale_date) = '$todaysDate'";
$salesResult = mysqli_query($conn, $salesQuery);
if ($salesResult) {
    $response['todaysSales'] = number_format(mysqli_fetch_assoc($salesResult)['total'] ?? 0, 2);
}

// Pending purchase orders
$poQuery = "SELECT COUNT(*) AS pending FROM purchase_orders WHERE status = 'Pending'";
$poResult = mysqli_query($conn, $poQuery);
if ($poResult) {
    $response['pendingPOs'] = mysqli_fetch_assoc($poResult)['pending'];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
