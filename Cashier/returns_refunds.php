<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'cashier') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Returns & Refunds | GreenChoice Market</title>
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
      padding: 10px;
      background-color: #d32f2f;
      color: white;
      border-radius: 8px;
      text-decoration: none;
      text-align: center;
    }
    .logout-btn:hover {
      background-color: #b71c1c;
    }
    .main-content {
      flex: 1;
      padding: 30px;
      margin-left: 250px;
      overflow-y: auto;
      height: 100vh;
    }
    .btn-success {
      background-color: #2e7d32;
    }
    .btn-success:hover {
      background-color: #1b5e20;
    }
    table th {
      background-color: #2e7d32;
      color: white;
    }
    .back-btn {
      float: right;
      margin-bottom: 15px;
    }
  </style>

  <script>
document.addEventListener('DOMContentLoaded', () => {
  const returnForm = document.getElementById('returnForm');
  const productNameInput = document.getElementById('productName');
  const productIdInput = document.getElementById('productId');

  // product_id lookup on blur
  productNameInput.addEventListener('blur', async () => {
    const productName = productNameInput.value.trim();
    if (productName !== '') {
      const res = await fetch(`get_product_id.php?product_name=${encodeURIComponent(productName)}`);
      const id = await res.text();
      productIdInput.value = id;
    }
  });

  if (returnForm) {
    returnForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(returnForm);
      const response = await fetch("submit_return.php", {
        method: "POST",
        body: formData
      });

      const result = await response.text();
      if (result.trim() === "success") {
        alert("Return submitted successfully.");
        location.reload();
      } else {
        alert("Submission failed: " + result);
      }
    });
  }
});
</script>

</head>

<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <div>
      <h4>GreenChoice Cashier</h4>
      <div class="nav-links">
        <a href="cashier_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="sales_billing.php"><i class="bi bi-credit-card-2-front"></i> Sales & Billing</a>
        <a href="stock_view.php"><i class="bi bi-search"></i> Stock View</a>
        <a href="returns_refunds.php" class="active"><i class="bi bi-arrow-return-left"></i> Returns & Refunds</a>
        <a href="sales_reports.php"><i class="bi bi-file-earmark-text"></i> Reports</a>
        <a href="alerts.php"><i class="bi bi-bell-fill"></i> Alerts</a>
      </div>
    </div>

    <a href="../logout.php" class="logout-btn d-flex align-items-center justify-content-center">
      <i class="bi bi-box-arrow-right me-2"></i> Log Out
    </a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <a href="cashier_dashboard.php" class="btn btn-outline-success back-btn">
      <i class="bi bi-arrow-left-circle"></i> Back
    </a>
    <h2 class="mb-4 text-center">↩ Returns & Refunds</h2>

    <!-- Return Form -->
    <div class="d-flex justify-content-center align-items-start">
      <div class="card mb-4 w-100" style="max-width: 800px;">
        <div class="card-body">
          <h5 class="card-title">Return a Product</h5>
          <form action="submit_return.php" method="POST" id="returnForm">
            <div class="row g-3">
              <div class="col-md-4">
                <label for="invoiceId" class="form-label">Invoice ID</label>
                <input type="text" class="form-control" name="invoice_id" id="invoiceId" required />
              </div>
              <input type="hidden" name="product_id" id="productId" />

              <div class="col-md-4">
                <label for="productName" class="form-label">Product Name</label>
                <input type="text" class="form-control" name="product_name" id="productName" required />
              </div>
              <div class="col-md-2">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" name="quantity" id="quantity" required min="1" />
              </div>
              <div class="col-md-6">
                <label for="reason" class="form-label">Reason for Return</label>
                <input type="text" class="form-control" name="reason" id="reason" required />
              </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Submit Return</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Return History -->
    <div class="card mt-4 w-100" style="max-width: 1000px; margin: auto;">
      <div class="card-body">
        <h5 class="card-title">Return History</h5>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Invoice ID</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Reason</th>
              <th>Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
            include '../db_connect.php';
            $result = $conn->query("SELECT * FROM returns ORDER BY return_date DESC");

            $i = 1;
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $return_id = htmlspecialchars($row['return_id']);
                    $invoice_id = htmlspecialchars($row['invoice_id']);
                    $product_name = htmlspecialchars($row['product_name']);
                    $quantity = htmlspecialchars($row['quantity']);
                    $reason = htmlspecialchars($row['reason']);
                    $return_date = htmlspecialchars($row['return_date']);
                    $status = htmlspecialchars($row['status']);

                    $status_class = 'warning text-dark';
                    if ($status === 'Approved') $status_class = 'success';
                    elseif ($status === 'Rejected') $status_class = 'danger';

                    echo "<tr>
                            <td>{$i}</td>
                            <td>{$invoice_id}</td>
                            <td>{$product_name}</td>
                            <td>{$quantity}</td>
                            <td>{$reason}</td>
                            <td>{$return_date}</td>
                            <td><span class='badge bg-{$status_class}'>{$status}</span></td>
                          </tr>";
                    $i++;
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No return records found.</td></tr>";
            }

            $conn->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
