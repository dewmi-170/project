<?php
session_start();
header('Content-Type: application/json');

// Role validation
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    echo json_encode([]);
    exit;
}

include '../db_connect.php'; // database connection file

$lowStockLimit = 5;

// Prepared statement
$sql = "SELECT product_id, product_name, stock_qty FROM products WHERE stock_qty <= ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("i", $lowStockLimit);
$stmt->execute();
$result = $stmt->get_result();

$lowStockItems = [];
while ($row = $result->fetch_assoc()) {
    $lowStockItems[] = $row;
}

// Return JSON
echo json_encode($lowStockItems);

// Cleanup
$stmt->close();
$conn->close();
?>
