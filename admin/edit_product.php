<?php
session_start();
include '../db_connect.php';

// Role validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

$product_id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE product_id = '$product_id'");

if ($result->num_rows == 0) {
  header("Location: product_management.php");
  exit;
}

$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $product_name = $_POST['product_name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock_qty = $_POST['stock_qty'];
  $status = $_POST['status'];

  $sql = "UPDATE products 
          SET product_name='$product_name', category='$category', price='$price', stock_qty='$stock_qty', status='$status' 
          WHERE product_id='$product_id'";

  if ($conn->query($sql) === TRUE) {
    header("Location: product_management.php");
    exit;
  } else {
    echo "Error: " . $conn->error;
  }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Edit Product</h2>
  <form method="POST">
    <div class="mb-3">
      <label for="product_name" class="form-label">Product Name</label>
      <input type="text" class="form-control" id="name" name="product_name" value="<?= $product['product_name'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input type="text" class="form-control" id="category" name="category" value="<?= $product['category'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Price (Rs)</label>
      <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?= $product['price'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="stock_qty" class="form-label">Stock Quantity</label>
      <input type="number" class="form-control" id="stock_qty" name="stock_qty" value="<?= $product['stock_qty'] ?>" required>
    </div>
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select class="form-select" id="status" name="status">
        <option value="Active" <?= $product['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
        <option value="Inactive" <?= $product['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
    <a href="product_management.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
