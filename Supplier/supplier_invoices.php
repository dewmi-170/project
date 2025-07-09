<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Supplier Invoices | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <style>
    body {
      background-color: #f1fdf3;
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      display: flex;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #2e7d32;
      padding: 20px;
      color: #fff;
      position: fixed;
    }
    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar a {
      display: block;
      color: #fff;
      padding: 10px 15px;
      margin-bottom: 10px;
      text-decoration: none;
      border-radius: 8px;
      transition: background 0.3s;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1b5e20;
      font-weight: bold;
    }
    .main-content {
      margin-left: 270px;
      padding: 40px;
      width: 100%;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 40px;
    }
    .card-header {
      background-color: #2e7d32;
      color: white;
      font-weight: 600;
    }
    .status-paid {
      background-color: #c8e6c9;
      color: #1b5e20;
      padding: 5px 10px;
      border-radius: 10px;
      font-weight: bold;
    }
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
      padding: 5px 10px;
      border-radius: 10px;
      font-weight: bold;
    }
    .status-overdue {
      background-color: #f8d7da;
      color: #721c24;
      padding: 5px 10px;
      border-radius: 10px;
      font-weight: bold;
    }
    .table th {
      background-color: #e8f5e9;
    }
    .btn-view {
      color: #2e7d32;
      border-color: #2e7d32;
    }
    .btn-view:hover {
      background-color: #2e7d32;
      color: white;
    }
    .btn-back {
      background-color: #c62828;
      color: white;
      padding: 8px 15px;
      border-radius: 8px;
      text-decoration: none;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php"><i class="bi bi-bag-check"></i> View Orders</a>
  <a href="manage_products.php"><i class="bi bi-tags"></i> Manage Products</a>
  <a href="shipping_documents.php"><i class="bi bi-truck"></i> Shipping</a>
  <a href="supplier_communication.php"><i class="bi bi-chat-dots"></i> Communication</a>
  <a href="supplier_invoices.php" class="active"><i class="bi bi-receipt"></i> Invoice Tracking</a>
  <a href="supplier_notifications.php"><i class="bi bi-bell"></i> Notifications</a>
  <hr>
  <a href="../logout.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
  <div class="container">

    <?php
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-info">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
    }
    ?>

    <div class="card">
      <div class="card-header">🧾 Supplier Invoices</div>
      <div class="card-body">
        <p class="mb-4">Track all your invoices, payment statuses, and due dates.</p>

        <table class="table table-hover" id="invoiceTable">
          <thead>
            <tr>
              <th>#Invoice ID</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Due Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Dynamic Content Loaded Here -->
          </tbody>
        </table>

        <a href="supplier_dashboard.php" class="btn-back mt-3"><i class="bi bi-arrow-left-circle"></i> Back</a>
      </div>
    </div>

    <div class="card">
      <div class="card-header">➕ Add New Invoice</div>
      <div class="card-body">
        <form method="POST" action="insert_invoice.php" id="addInvoiceForm">
          <div class="mb-3">
            <label for="invoice_id" class="form-label">Invoice ID</label>
            <input type="text" class="form-control" name="invoice_id" id="invoice_id" required />
          </div>
          <div class="mb-3">
            <label for="invoice_date" class="form-label">Invoice Date</label>
            <input type="date" class="form-control" name="invoice_date" id="invoice_date" required />
          </div>
          <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" min="0" class="form-control" name="amount" id="amount" required />
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" name="status" id="status" required>
              <option value="Pending" selected>Pending</option>
              <option value="Paid">Paid</option>
              <option value="Overdue">Overdue</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="due_date" class="form-label">Due Date</label>
            <input type="date" class="form-control" name="due_date" id="due_date" required />
          </div>
          <button type="submit" class="btn btn-success">Add Invoice</button>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
function loadInvoices() {
  $.ajax({
    url: 'fetch_invoices.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      let rows = '';
      data.forEach(inv => {
        let statusClass = inv.status === 'Paid' ? 'status-paid' :
                          inv.status === 'Overdue' ? 'status-overdue' : 'status-pending';

        rows += `
          <tr>
            <td>${inv.invoice_id}</td>
            <td>${inv.invoice_date}</td>
            <td>$${parseFloat(inv.amount).toFixed(2)}</td>
            <td><span class="${statusClass}">${inv.status}</span></td>
            <td>${inv.due_date}</td>
            <td><a href="invoice_view.php?id=${inv.id}" class="btn btn-sm btn-view">View</a></td>
          </tr>
        `;
      });
      $('#invoiceTable tbody').html(rows);
    },
    error: function(xhr, status, error) {
      console.error("Error loading invoices:", error);
    }
  });
}

// Load invoices initially and refresh every 10 seconds
loadInvoices();
setInterval(loadInvoices, 10000);
</script>

</body>
</html>
