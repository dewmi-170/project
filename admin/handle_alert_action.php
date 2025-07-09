<?php
session_start();
include '../db_connect.php';

// Check if alert is sent
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_alert'])) {
    $product_id = intval($_POST['send_alert']);
    $user_id = $_SESSION['user_id'] ?? 1; // fallback for testing

    // Log alert in a table (optional - good for audit trail)
    $stmt = $conn->prepare("INSERT INTO alerts (product_id, sent_by, sent_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();

    // Redirect back with success toast
    header("Location: notifications.php?alert=sent&product_id=$product_id");
    exit;
} else {
    // Invalid request
    header("Location: notifications.php?alert=invalid");
    exit;
}
?>
