<?php
require '../db_connect.php'; // Adjust path as needed

// Get product by ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Product ID!";
    exit;
}

$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "Product not found!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['product_name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];
    $status = $_POST['status'];

    $update = $conn->prepare("UPDATE products SET product_name=?, category=?, price=?, stock_qty=?, status=? WHERE product_id=?");
    $update->bind_param("ssdiss", $name, $category, $price, $stock_qty, $status, $product_id);

    if ($update->execute()) {
        header("Location: stock_operations.php?msg=Product+Updated+Successfully");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>✏ Edit Product</h2>
    <form method="POST" class="mt-3">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" value="<?= htmlspecialchars($product['product_name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Price (Rs.)</label>
            <input type="number" name="price" step="0.01" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Stock Quantity</label>
            <input type="number" name="stock_qty" class="form-control" value="<?= $product['stock_qty'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="Active" <?= $product['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $product['status'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">💾 Save Changes</button>
        <a href="stock_operations.php" class="btn btn-secondary">↩ Back</a>
    </form>
</div>
</body>
</html>
