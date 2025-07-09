<?php
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
$order_id = $_GET['order_id'];
$conn->query("UPDATE purchase_orders SET status = 'Cancelled' WHERE order_id = '$order_id'");
header("Location: view_orders.php");
