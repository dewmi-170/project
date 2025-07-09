<?php
require_once('../config/db.php');

if (!isset($_GET['product_id'])) {
    echo "Product ID not provided.";
    exit;
}

$product_id = (int)$_GET['product_id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Product Details</h3>
  <table class="table table-bordered">
    <tr>
      <th>Product ID</th>
      <td><?= $product['product_id']; ?></td>
    </tr>
    <tr>
      <th>Name</th>
      <td><?= $product['name']; ?></td>
    </tr>
    <tr>
      <th>Category</th>
      <td><?= $product['category']; ?></td>
    </tr>
    <tr>
      <th>Stock Quantity</th>
      <td><?= $product['stock_qty']; ?></td>
    </tr>
    <tr>
      <th>Last Updated</th>
      <td><?= $product['last_updated']; ?></td>
    </tr>
  </table>
  <a href="inventory_oversight.php" class="btn btn-secondary">Back to Inventory</a>
</div>
</body>
</html>
