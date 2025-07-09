<?php
include '../db_connect.php';

$threshold = 5;
$lowStockToasts = [];

$sql = "
  SELECT p.product_id, p.product_name, p.stock_qty 
  FROM products p 
  WHERE p.stock_qty <= $threshold 
    AND NOT EXISTS (
      SELECT 1 FROM low_stock_notifications n 
      WHERE n.product_id = p.product_id
    )
";

$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $msg = "⚠️ <strong>{$row['product_name']}</strong> stock is low ({$row['stock_qty']} left)";
        $lowStockToasts[] = $msg;

        $pid = $row['product_id'];
        $conn->query("INSERT INTO low_stock_notifications (product_id) VALUES ($pid)");
    }
}

header('Content-Type: application/json');
echo json_encode($lowStockToasts);
