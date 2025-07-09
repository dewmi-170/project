<?php
include '../db_connect.php';

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $stock_qty = $_POST['stock_qty'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO products (name, category, price, stock_qty, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $name, $category, $price, $stock_qty, $status);

    if ($stmt->execute()) {
        header("Location: product_management.php?success=Product added successfully");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
