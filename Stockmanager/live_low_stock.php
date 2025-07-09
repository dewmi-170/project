<?php
include '../db_connect.php';

$threshold = 5;
$lowStockToasts = [];

$sql = "
  SELECT p.product_id, p.product_name, p.stock_qty 
  FROM products p 
  WHERE p.stock_qty <= ? 
    AND NOT EXISTS (
      SELECT 1 FROM low_stock_notifications n 
      WHERE n.product_id = p.product_id
    )
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $threshold);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $msg = "⚠️ <strong>{$row['product_name']}</strong> stock is low ({$row['stock_qty']} left)";
    $lowStockToasts[] = $msg;

    $insertStmt = $conn->prepare("INSERT INTO low_stock_notifications (product_id, notified_at) VALUES (?, NOW())");
    $insertStmt->bind_param("i", $row['product_id']);
    $insertStmt->execute();
    $insertStmt->close();
}

$stmt->close();

header('Content-Type: application/json');
echo json_encode($lowStockToasts);
