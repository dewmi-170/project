<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([]);
    exit;
}

$supplier_id = $_SESSION['user_id'];

$sql = "SELECT id, invoice_id, invoice_date, amount, status, due_date FROM supplier_invoices WHERE supplier_id = ? ORDER BY invoice_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();

$invoices = [];
while ($row = $result->fetch_assoc()) {
    $invoices[] = $row;
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($invoices);
