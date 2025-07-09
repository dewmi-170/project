<?php include '../db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Sales & Billing | GreenChoice Cashier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      display: flex;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0fdf4;
      height: 100vh;
      overflow: hidden;
    }
    .sidebar {
      width: 250px;
      background-color: #256029;
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 20px;
      position: fixed;
      height: 100vh;
    }
    .sidebar h4 {
      text-align: center;
      font-size: 1.6rem;
      font-weight: bold;
      margin-bottom: 40px;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #fff;
      padding: 12px 15px;
      margin-bottom: 12px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .sidebar a i {
      margin-right: 10px;
      font-size: 1.3rem;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1a4f20;
      padding-left: 20px;
    }
    .logout-btn {
      background-color: #c62828;
      color: #fff;
      font-weight: bold;
      text-align: center;
      padding: 10px 0;
      border-radius: 8px;
      transition: background-color 0.3s ease;
      text-decoration: none;
    }
    .logout-btn:hover {
      background-color: #b71c1c;
    }
    .main-content {
      margin-left: 260px;
      padding: 30px;
      width: 100%;
      overflow-y: auto;
    }
    .invoice-card {
      background-color: white;
      border-radius: 15px;
      padding: 25px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .btn-success {
      background-color: #2e7d32;
      border-color: #2e7d32;
    }
    .btn-success:hover {
      background-color: #1b5e20;
      border-color: #1b5e20;
    }
    .back-btn {
      position: fixed;
      bottom: 20px;
      right: 30px;
      background-color: #2e7d32;
      border: none;
      padding: 10px 20px;
      color: white;
      border-radius: 30px;
      font-weight: bold;
      box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
      transition: 0.3s ease;
      cursor: pointer;
      text-decoration: none;
    }
    .back-btn:hover {
      background-color: #1b5e20;
    }
    .item-row input,
    .item-row select {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div>
      <h4><i class="bi bi-person-badge"></i> Cashier Panel</h4>
      <a href="cashier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="sales_billing.php" class="active"><i class="bi bi-credit-card"></i> Sales & Billing</a>
      <a href="stock_view.php"><i class="bi bi-box-seam"></i> Stock View</a>
      <a href="returns_refunds.php"><i class="bi bi-arrow-return-left"></i> Returns & Refunds</a>
      <a href="sales_reports.php"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
      <a href="alerts.php"><i class="bi bi-bell"></i> Alerts</a>
    </div>
    <a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </div>

  <div class="main-content">
    <h2 class="text-success mb-4">💳 Sales & Billing</h2>
    <div class="invoice-card">
      <form action="process_sale.php" method="POST" id="saleForm">
        <div class="mb-3">
          <label class="form-label">Invoice ID</label>
          <input
            type="text"
            name="invoice_id"
            class="form-control"
            readonly
            value="INV<?= date('YmdHis') ?>"
          />
        </div>
        <div class="mb-3">
          <label class="form-label">Cashier Name</label>
          <input
            type="text"
            name="cashier_name"
            class="form-control"
            required
            placeholder="Enter cashier name"
          />
        </div>

        <div id="itemsContainer">
          <div class="row item-row">
            <div class="col-md-5">
              <select
                class="form-select product-select"
                name="product_id[]"
                required
              >
                <option value="">-- Select Product --</option>
                <?php
                $result = $conn->query("SELECT * FROM products WHERE status='active'");
                while ($row = $result->fetch_assoc()) {
                  $product_id = $row['product_id'];
                  $product_name = $row['product_name'];
                  $product_price = number_format($row['price'], 2);
                  echo "<option value='{$product_id}' data-price='{$row['price']}'>
                          {$product_id} - {$product_name} - Rs.{$product_price}
                        </option>";
                }
                ?>
              </select>
            </div>
            <div class="col-md-2">
              <input
                type="number"
                class="form-control quantity"
                name="quantity[]"
                min="1"
                required
                placeholder="Qty"
              />
            </div>
            <div class="col-md-3">
              <input
                type="text"
                class="form-control subtotal"
                name="subtotal[]"
                readonly
                placeholder="Rs.0.00"
              />
            </div>
            <div class="col-md-2 d-flex align-items-center">
              <button
                type="button"
                class="btn btn-danger remove-item"
                title="Remove item"
              >
                <i class="bi bi-x-circle"></i>
              </button>
            </div>
          </div>
        </div>

        <button
          type="button"
          class="btn btn-outline-primary my-3"
          id="addItemBtn"
        >
          <i class="bi bi-plus-circle"></i> Add Item
        </button>

        <div class="mb-3">
          <label class="form-label">Total Amount</label>
          <input
            type="text"
            class="form-control"
            name="total_amount"
            id="grandTotal"
            readonly
            value="Rs.0.00"
          />
        </div>

        <div class="mb-3">
          <label class="form-label">Payment Method</label>
          <select
            name="payment_method"
            class="form-select"
            required
          >
            <option value="">-- Select Method --</option>
            <option value="Cash">Cash</option>
            <option value="Card">Card</option>
            <option value="Mobile Payment">Mobile Payment</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success">Confirm & Print</button>
        <button type="reset" class="btn btn-secondary ms-2">Cancel</button>
      </form>
    </div>
  </div>

  <a href="cashier_dashboard.php" class="back-btn"><i class="bi bi-arrow-left-circle me-2"></i>Back</a>

  <script>
    function calculateSubtotals() {
      let grandTotal = 0;
      document.querySelectorAll('.item-row').forEach(row => {
        const productSelect = row.querySelector('.product-select');
        const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
        const price = parseFloat(
          productSelect.selectedOptions[0]?.getAttribute('data-price')
        ) || 0;
        const subtotal = price * quantity;
        row.querySelector('.subtotal').value = "Rs." + subtotal.toFixed(2);
        grandTotal += subtotal;
      });
      document.getElementById('grandTotal').value = "Rs." + grandTotal.toFixed(2);
    }

    document.addEventListener('input', e => {
      if (
        e.target.classList.contains('quantity') ||
        e.target.classList.contains('product-select')
      ) {
        calculateSubtotals();
      }
    });

    document.getElementById('addItemBtn').addEventListener('click', () => {
      const firstRow = document.querySelector('.item-row');
      const newRow = firstRow.cloneNode(true);

      newRow.querySelectorAll('input').forEach(input => (input.value = ''));
      newRow.querySelectorAll('select').forEach(select => (select.selectedIndex = 0));

      document.getElementById('itemsContainer').appendChild(newRow);
      calculateSubtotals();
    });

    document.getElementById('itemsContainer').addEventListener('click', e => {
      if (
        e.target.classList.contains('remove-item') &&
        document.querySelectorAll('.item-row').length > 1
      ) {
        e.target.closest('.item-row').remove();
        calculateSubtotals();
      }
    });

    // Initialize totals on page load
    window.onload = () => calculateSubtotals();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
