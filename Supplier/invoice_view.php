<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$invoice_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$supplier_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM supplier_invoices WHERE id = ? AND supplier_id = ?");
$stmt->bind_param("ii", $invoice_id, $supplier_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Invoice not found!";
    exit;
}

$invoice = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #<?= htmlspecialchars($invoice['invoice_id']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f1fdf3; font-family: 'Segoe UI'; }
    .container { max-width: 700px; margin-top: 50px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .invoice-header { border-bottom: 2px solid #2e7d32; margin-bottom: 20px; padding-bottom: 10px; }
    .invoice-title { font-size: 24px; font-weight: bold; color: #2e7d32; }
    .btn-back { background: #2e7d32; color: white; border: none; padding: 8px 16px; border-radius: 8px; }
    .btn-back:hover { background: #1b5e20; }
  </style>
</head>
<body>

<div class="container">
  <div class="invoice-header">
    <div class="invoice-title">🧾 Invoice #<?= htmlspecialchars($invoice['invoice_id']) ?></div>
  </div>

  <p><strong>Date:</strong> <?= $invoice['invoice_date'] ?></p>
  <p><strong>Amount:</strong> $<?= number_format($invoice['amount'], 2) ?></p>
  <p><strong>Status:</strong> <?= $invoice['status'] ?></p>
  <p><strong>Due Date:</strong> <?= $invoice['due_date'] ?></p>

  <hr>
  <a href="supplier_invoices.php" class="btn-back mt-3"><i class="bi bi-arrow-left"></i> Back to Invoices</a>
</div>

</body>
</html>
