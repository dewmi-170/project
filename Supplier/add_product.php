<?php
include '../db_connect.php';
session_start();

$supplier_id = 1; // Or use: $_SESSION['user_id']

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['product_name']);
    $price = floatval($_POST['price']);
    $availability = $_POST['availability'];
    $lead_time = intval($_POST['lead_time']);

    if (!empty($name) && $price >= 0 && $lead_time >= 0) {
        $stmt = $conn->prepare("INSERT INTO supplier_products (supplier_id, product_name, price, availability, lead_time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsi", $supplier_id, $name, $price, $availability, $lead_time);
        $stmt->execute();
        $stmt->close();
        header("Location: manage_products.php?success=1");
        exit;
    } else {
        echo "Invalid input!";
    }
} else {
    echo "Invalid request method.";
}
?>
