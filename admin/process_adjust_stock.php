<?php
session_start();
require_once('../config/db.php'); 


$product_id = $_POST['product_id'];
$adjustment = $_POST['adjustment'];
$reason = $_POST['reason'];
$adjusted_by = $_SESSION['username'] ?? 'admin'; // fallback if session username is not set

// Update stock level in products table
$stmt1 = $conn->prepare("UPDATE products SET stock_qty = stock_qty + ? WHERE product_id = ?");
$stmt1->bind_param("ii", $adjustment, $product_id);
$stmt1->execute();

// Insert into stock_adjustments table
$stmt2 = $conn->prepare("INSERT INTO stock_adjustments (product_id, adjustment_qty, reason, adjusted_by) VALUES (?, ?, ?, ?)");
$stmt2->bind_param("iiss", $product_id, $adjustment, $reason, $adjusted_by);
$stmt2->execute();

$stmt1->close();
$stmt2->close();
$conn->close();

header("Location: inventory_oversight.php?success=1");
exit;
