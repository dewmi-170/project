<?php
// DB Connection
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulated logged-in supplier ID
$supplier_id = 1;

$query = "SELECT * FROM supplier_products WHERE supplier_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $supplier_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products | Supplier Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #eef6f1;
      font-family: 'Segoe UI', sans-serif;
    }
    .main-wrapper {
      display: flex;
      justify-content: center;
      padding: 50px 20px;
      margin-left: 250px;
    }
    .content-container {
      max-width: 1000px;
      width: 100%;
    }
    .sidebar {
      width: 250px;
      height: 100vh;
      background-color: #2e7d32;
      position: fixed;
      top: 0;
      left: 0;
      padding: 30px 20px;
      color: white;
    }
    .sidebar h4 {
      font-size: 22px;
      margin-bottom: 30px;
      text-align: center;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 10px;
      color: white;
      text-decoration: none;
      margin-bottom: 10px;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    .sidebar a.active,
    .sidebar a:hover {
      background-color: #1b5e20;
      font-weight: bold;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      background-color: white;
    }
    th {
      background-color: #007bff;
      color: white;
    }
    .btn-update {
      background-color: #28a745;
      color: white;
      border: none;
    }
    .btn-update:hover {
      background-color: #218838;
    }
    .btn-back {
      background-color: #6c757d;
      color: white;
      border: none;
    }
    .btn-back:hover {
      background-color: #5a6268;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php"><i class="bi bi-bag-check-fill"></i> View Orders</a>
  <a href="manage_products.php" class="active"><i class="bi bi-tags-fill"></i> Manage Products</a>
  <a href="shipping_documents.php"><i class="bi bi-truck"></i> Shipping & Docs</a>
  <a href="supplier_communication.php"><i class="bi bi-chat-dots-fill"></i> Communication</a>
  <a href="supplier_invoices.php"><i class="bi bi-receipt-cutoff"></i> Invoice Tracking</a>
  <a href="supplier_notifications.php"><i class="bi bi-bell-fill"></i> Notifications</a>
  <hr>
  <a href="../logout.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-wrapper">
  <div class="content-container">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="fas fa-tags"></i> Manage Products</h2>
      <a href="supplier_dashboard.php" class="btn btn-back"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <!-- Success Message -->
    <?php if (isset($_GET['success'])): ?>
      <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> Product added or updated successfully!</div>
    <?php endif; ?>

    <!-- Add Product Form -->
    <div class="card p-4 mb-4">
      <h5><i class="bi bi-plus-circle-fill"></i> Add New Product</h5>
      <form method="post" action="add_product.php">
        <div class="row g-3">
          <div class="col-md-4">
            <input type="text" class="form-control" name="product_name" placeholder="Product Name" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" class="form-control" name="price" placeholder="Price (Rs)" required>
          </div>
          <div class="col-md-3">
            <select name="availability" class="form-select">
              <option value="Available">Available</option>
              <option value="Out of Stock">Out of Stock</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control" name="lead_time" placeholder="Lead Time (Days)" required>
          </div>
          <div class="col-md-1">
            <button type="submit" class="btn btn-success w-100"><i class="bi bi-check-circle-fill"></i></button>
          </div>
        </div>
      </form>
    </div>

    <!-- Products Table -->
    <div class="card p-4">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th><i class="fas fa-box-open"></i> Product</th>
            <th><i class="fas fa-money-bill-wave"></i> Price (Rs)</th>
            <th><i class="fas fa-warehouse"></i> Availability</th>
            <th><i class="fas fa-clock"></i> Lead Time (Days)</th>
            <th><i class="fas fa-cogs"></i> Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <form method="post" action="update_product.php">
              <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
              <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><input type="number" class="form-control" name="price" value="<?= $row['price'] ?>" required></td>
                <td>
                  <select class="form-select" name="availability">
                    <option value="Available" <?= $row['availability'] == 'Available' ? 'selected' : '' ?>>Available</option>
                    <option value="Out of Stock" <?= $row['availability'] == 'Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
                  </select>
                </td>
                <td><input type="number" class="form-control" name="lead_time" value="<?= $row['lead_time'] ?>" required></td>
                <td><button type="submit" class="btn btn-update"><i class="fas fa-save"></i> Update</button></td>
              </tr>
            </form>
          <?php } ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

</body>
</html>
