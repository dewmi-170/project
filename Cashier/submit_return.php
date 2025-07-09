<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    echo "Unauthorized access.";
    exit;
}

include '../db_connect.php';

// Get POST data safely
$invoice_id   = trim($_POST['invoice_id'] ?? '');
$product_name = trim($_POST['product_name'] ?? '');
$quantity     = intval($_POST['quantity'] ?? 0);
$reason       = trim($_POST['reason'] ?? '');
$returned_by  = $_SESSION['username'] ?? 'unknown';

// Validate required fields
if (empty($invoice_id) || empty($product_name) || $quantity <= 0 || empty($reason)) {
    echo "Please fill all fields correctly.";
    exit;
}

// Check if invoice_id exists in sales or invoices table (adjust table name accordingly)
$checkInvoice = $conn->prepare("SELECT COUNT(*) FROM sales WHERE invoice_id = ?");
$checkInvoice->bind_param("s", $invoice_id);
$checkInvoice->execute();
$checkInvoice->bind_result($invoiceExists);
$checkInvoice->fetch();
$checkInvoice->close();

if ($invoiceExists == 0) {
    echo "Invalid invoice ID.";
    exit;
}

// Check if product_name exists in products table
$checkProduct = $conn->prepare("SELECT COUNT(*) FROM products WHERE product_name = ?");
$checkProduct->bind_param("s", $product_name);
$checkProduct->execute();
$checkProduct->bind_result($productExists);
$checkProduct->fetch();
$checkProduct->close();

if ($productExists == 0) {
    echo "Invalid product name.";
    exit;
}

// Prepare insert statement for returns table
$stmt = $conn->prepare("INSERT INTO returns (invoice_id, product_name, quantity, reason, return_date, status, returned_by) VALUES (?, ?, ?, ?, NOW(), 'Pending', ?)");
if (!$stmt) {
    echo "Prepare failed: " . $conn->error;
    exit;
}

// Bind parameters and execute
$stmt->bind_param("ssiss", $invoice_id, $product_name, $quantity, $reason, $returned_by);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
