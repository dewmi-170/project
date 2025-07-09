<?php
include '../db_connect.php';

header('Content-Type: application/json');

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($query === '') {
    // Empty query returns all products
    $sql = "SELECT product_id, product_name, stock_qty FROM products ORDER BY product_name ASC";
    $stmt = $conn->prepare($sql);
} else {
    // Search by product_id or product_name (partial match)
    $sql = "SELECT product_id, product_name, stock_qty FROM products WHERE product_id LIKE ? OR product_name LIKE ? ORDER BY product_name ASC";
    $searchTerm = "%".$query."%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
}

$stmt->execute();
$result = $stmt->get_result();

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);

$stmt->close();
$conn->close();
