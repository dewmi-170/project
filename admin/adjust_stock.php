<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Adjust Stock</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <div class="card p-4 shadow-sm">
    <h3 class="mb-4">Adjust Stock</h3>

    <form method="POST" action="process_adjust_stock.php">
      <div class="mb-3">
        <label for="product_id" class="form-label">Select Product</label>
        <select class="form-select" name="product_id" required>
  <?php
  require_once('../config/db.php');
  $result = $conn->query("SELECT product_id, product_name FROM products");

  if (!$result) {
      echo "<option disabled>Error loading products</option>";
  } else {
      while ($row = $result->fetch_assoc()) {
          echo "<option value='{$row['product_id']}'>{$row['product_id']} - {$row['product_name']}</option>";
      }
  }
  ?>
</select>

      </div>

      <div class="mb-3">
        <label for="adjustment" class="form-label">Adjustment Quantity (+/-)</label>
        <input type="number" class="form-control" name="adjustment" required>
      </div>

      <div class="mb-3">
        <label for="reason" class="form-label">Reason</label>
        <textarea class="form-control" name="reason" rows="3" required></textarea>
      </div>

      <button type="submit" class="btn btn-success">Save Adjustment</button>
      <a href="inventory_oversight.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</div>

</body>
</html>
