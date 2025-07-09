<?php
include '../db_connect.php';

if (!isset($_GET['invoice_id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = $_GET['invoice_id'];

$sql = "SELECT s.*, p.product_name, p.price
        FROM sales s 
        JOIN products p ON s.product_id = p.product_id
        WHERE s.invoice_id = '$invoice_id'";

$result = $conn->query($sql);

if (!$result) {
    die("Query Error: " . $conn->error);
}

$data = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
  <title>Invoice #<?= $invoice_id ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .invoice-box { max-width: 600px; margin: auto; padding: 30px; background: white; border-radius: 8px; }
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body class="bg-light">
<div class="invoice-box">
  <h4>🧾 Invoice #: <?= $invoice_id ?></h4>
  <p><strong>Date:</strong> <?= $data['sale_date'] ?></p>
  <p><strong>Cashier:</strong> <?= $data['cashier_name'] ?></p>
  <hr>
  <p><strong>Product:</strong> <?= $data['product_name'] ?></p>
  <p><strong>Unit Price:</strong> Rs.<?= $data['price'] ?></p>
  <p><strong>Quantity:</strong> <?= $data['quantity'] ?></p>
  <p><strong>Total:</strong> <span class="text-success">Rs.<?= $data['total_amount'] ?></span></p>
  <hr>
  <button onclick="window.print()" class="btn btn-primary no-print">🖨️ Print</button>
  <a href="sales_billing.php" class="btn btn-secondary no-print">⬅️ Back</a>
</div>
</body>
</html>
