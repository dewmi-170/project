<?php
// approve_order.php

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'greenchoice_db');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update order status to "Approved"
    $sql = "UPDATE purchase_orders SET status='Approved' WHERE order_id='$order_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Order Approved Successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>
