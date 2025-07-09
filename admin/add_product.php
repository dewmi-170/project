<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $product_name = $_POST['product_name'];
  $category = $_POST['category'];
  $price = $_POST['price'];
  $stock_qty = $_POST['stock_qty'];
  $status = $_POST['status'];

  $sql = "INSERT INTO products (product_name, category, price, stock_qty, status) 
          VALUES ('$product_name', '$category', '$price', '$stock_qty', '$status')";
  
  if ($conn->query($sql) === TRUE) {
    header("Location: product_management.php?msg=Product added successfully");
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
  <title>Add Product | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>Add Product</h2>
  <form method="POST">
    <div class="mb-3">
      <label for="product_name" class="form-label">Product Name</label>
      <input type="text" class="form-control" id="product_name" name="product_name" required>
    </div>
    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input type="text" class="form-control" id="category" name="category" required>
    </div>
    <div class="mb-3">
      <label for="price" class="form-label">Price (Rs)</label>
      <input type="number" step="0.01" class="form-control" id="price" name="price" required>
    </div>
    <div class="mb-3">
      <label for="stock_qty" class="form-label">Stock Quantity</label>
      <input type="number" class="form-control" id="stock_qty" name="stock_qty" required>
    </div>
    <div class="mb-3">
      <label for="status" class="form-label">Status</label>
      <select class="form-select" id="status" name="status">
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Add Product</button>
    <a href="product_management.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

</body>
</html>
