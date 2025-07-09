<?php
// invoice.php

if (isset($_GET['sale_id'])) {
    $sale_id = $_GET['sale_id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'greenchoice_db');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch sale details
    $sql = "SELECT * FROM sales WHERE id = '$sale_id'";
    $result = $conn->query($sql);
    $sale = $result->fetch_assoc();

    echo "<h2>Invoice</h2>";
    echo "Sale ID: " . $sale['id'] . "<br>";
    echo "Customer Name: " . $sale['customer_name'] . "<br>";
    echo "Product: " . $sale['product'] . "<br>";
    echo "Quantity: " . $sale['quantity'] . "<br>";
    echo "Total Amount: " . $sale['total_amount'] . "<br>";

    // Close connection
    $conn->close();
}
?>
