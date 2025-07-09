<?php
include '../db_connect.php';

if (!isset($_GET['invoice_id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = $_GET['invoice_id'];
$sales_data = [];

$res = $conn->query("SELECT s.*, p.product_name FROM sales s
                     JOIN products p ON s.product_id = p.product_id
                     WHERE s.invoice_id = '$invoice_id'");

if ($res->num_rows == 0) {
    die("No sales found for this invoice.");
}

$total_amount = 0;
$total_qty = 0;

while ($row = $res->fetch_assoc()) {
    $sales_data[] = $row;
    $total_amount += $row['total_price'];
    $total_qty += $row['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>POS Invoice - <?= htmlspecialchars($invoice_id) ?></title>
  <style>
    body { font-family: monospace; font-size: 14px; padding: 20px; }
    .receipt { max-width: 300px; margin: auto; padding: 10px; border: 1px dashed #000; }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    hr { border: none; border-top: 1px dashed black; margin: 10px 0; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 2px 0; }
    .totals td { padding-top: 5px; }
    .print-btn { text-align: center; margin-top: 20px; }
    @media print { .print-btn { display: none; } }
  </style>
</head>
<body>
<div class="receipt">
  <div class="center bold"><br>GreenChoice Market<br>Dambulla</div>
  <div class="center">TEL: 033-20 51 376</div>
  <hr>
  <table>
    <tr><td>Cashier:</td><td><?= htmlspecialchars($sales_data[0]['cashier_name']) ?></td></tr>
    <tr><td>Invoice ID:</td><td><?= htmlspecialchars($invoice_id) ?></td></tr>
    <tr><td>Payment:</td><td><?= htmlspecialchars($sales_data[0]['payment_method']) ?></td></tr>
  </table>
  <hr>
  <table>
    <tr class="bold">
      <td>#</td>
      <td>Product</td>
      <td>Price</td>
      <td>Qty</td>
      <td>Amount</td>
    </tr>
    <?php foreach ($sales_data as $i => $sale): ?>
      <tr>
        <td><?= $i + 1 ?>.</td>
        <td><?= htmlspecialchars($sale['product_name']) ?></td>
        <td><?= number_format($sale['total_price'] / $sale['quantity'], 2) ?></td>
        <td><?= $sale['quantity'] ?></td>
        <td><?= number_format($sale['total_price'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
  <hr>
  <table class="totals">
    <tr>
      <td><strong>TOTAL AMOUNT</strong></td>
      <td style="text-align: right;">Rs. <?= number_format($total_amount, 2) ?></td>
    </tr>
    <tr>
      <td><strong>CASH</strong></td>
      <td style="text-align: right;">Rs. <?= number_format($total_amount, 2) ?></td>
    </tr>
    <tr>
      <td><strong>BALANCE</strong></td>
      <td style="text-align: right;">Rs. 0.00</td>
    </tr>
  </table>
  <hr>
  <p>Total Products: <?= count($sales_data) ?> | Total Qty: <?= $total_qty ?></p>
  <p>Date: <?= date("d/m/Y") ?> | Time: <?= date("h:i:s A") ?></p>
  <p class="center">Thank You Come Again</p>
  <div class="center">System by MaxSoft / 0714 333 933</div>
</div>

<div class="print-btn">
  <button onclick="window.print()">🖨️ Print Invoice</button>

  <a href="process_return.php?invoice_id=<?= urlencode($invoice_id) ?>">
    <button>↩️ Return</button>
  </a>

  <a href="sales_billing.php">
    <button style="background-color: red; color: white;">❌ Cancel</button>
  </a>
</div>

</body>
</html>
