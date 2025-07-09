<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // ✅ Update delivered_at
    $stmt = $conn->prepare("UPDATE purchase_requests SET delivered_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ✅ Insert notification for Admin
    $message = "Purchase Order #$id marked as Delivered by Supplier.";
    $notif = $conn->prepare("INSERT INTO notifications (user_role, message) VALUES ('Admin', ?)");
    $notif->bind_param("s", $message);
    $notif->execute();

    header("Location: supplier_orders.php");
    exit();
}
?>
