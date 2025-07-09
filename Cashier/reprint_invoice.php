<?php
// Optional: session_start(); if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reprint Invoice | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      padding: 40px;
    }
    .container {
      max-width: 400px;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-control {
      font-size: 16px;
    }
    .btn {
      font-size: 16px;
    }
  </style>
</head>
<body>

<div class="container">
  <h4 class="mb-4 text-center">🔁 Reprint Invoice</h4>

  <form method="get" action="print_invoice.php">
    <div class="mb-3">
      <label for="invoice_id" class="form-label">Enter Invoice ID</label>
      <input type="text" class="form-control" name="invoice_id" id="invoice_id" placeholder="e.g. INV20250615-522" required>
    </div>

    <div class="d-grid">
      <button type="submit" class="btn btn-primary">🧾 Reprint Invoice</button>
    </div>
  </form>

  <div class="mt-3 text-center">
    <a href="sales_billing.php" class="btn btn-outline-secondary btn-sm">← Back to Billing</a>
  </div>
</div>

</body>
</html>
