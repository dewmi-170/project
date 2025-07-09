<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product = $_POST['product'];
    $quantity = $_POST['quantity'];
    $expected_date = $_POST['expected_date'];

    $stmt = $conn->prepare("INSERT INTO purchase_requests (product_name, quantity, expected_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $product, $quantity, $expected_date);

    if ($stmt->execute()) {
        header("Location: purchasing_oversight.php?success=1");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
