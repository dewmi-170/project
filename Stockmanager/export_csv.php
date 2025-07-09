<?php
include '../db_connect.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="products_export.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Product ID', 'Name', 'Category', 'Price', 'Stock Qty', 'Status']);

$result = $conn->query("SELECT * FROM products");
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['product_id'], $row['product_name'], $row['category'], $row['price'], $row['stock_qty'], $row['status']]);
}
fclose($output);
exit;
?>
