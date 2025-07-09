<?php
session_start();
require_once '../db_connect.php';

$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id == 0) {
    header("Location: ../login.php");
    exit();
}

// Sanitize checkbox values
$fields = [
    'low_stock' => isset($_POST['low_stock']) ? 1 : 0,
    'sales_summary' => isset($_POST['sales_summary']) ? 1 : 0,
    'purchase_requests' => isset($_POST['purchase_requests']) ? 1 : 0,
    'return_logs' => isset($_POST['return_logs']) ? 1 : 0,
    'stock_movement' => isset($_POST['stock_movement']) ? 1 : 0,
    'purchase_approvals' => isset($_POST['purchase_approvals']) ? 1 : 0
];

// Check if entry exists
$check = $conn->prepare("SELECT id FROM notification_preferences WHERE user_id = ?");
$check->bind_param("i", $user_id);
$check->execute();
$check_result = $check->get_result();

if ($check_result->num_rows > 0) {
    // Update existing
    $stmt = $conn->prepare("UPDATE notification_preferences 
        SET low_stock=?, sales_summary=?, purchase_requests=?, return_logs=?, stock_movement=?, purchase_approvals=? 
        WHERE user_id=?");
    $stmt->bind_param("iiiiiii", $fields['low_stock'], $fields['sales_summary'], $fields['purchase_requests'], 
        $fields['return_logs'], $fields['stock_movement'], $fields['purchase_approvals'], $user_id);
} else {
    // Insert new
    $stmt = $conn->prepare("INSERT INTO notification_preferences 
        (user_id, low_stock, sales_summary, purchase_requests, return_logs, stock_movement, purchase_approvals) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiiiii", $user_id, $fields['low_stock'], $fields['sales_summary'], 
        $fields['purchase_requests'], $fields['return_logs'], $fields['stock_movement'], $fields['purchase_approvals']);
}

if ($stmt->execute()) {
    header("Location: notifications.php?success=1");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
exit();
