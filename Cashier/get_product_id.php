<?php
include '../db_connect.php';

$product_name = trim($_GET['product_name'] ?? '');

if ($product_name !== '') {
    $stmt = $conn->prepare("SELECT product_id FROM products WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $stmt->bind_result($product_id);
    if ($stmt->fetch()) {
        echo $product_id;
    } else {
        echo 0;
    }
    $stmt->close();
}
$conn->close();
?>
