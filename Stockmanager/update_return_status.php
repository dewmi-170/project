<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli("localhost", "root", "", "supermarket_inventory");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $return_id = $_POST['return_id'] ?? '';
    $action = $_POST['action'] ?? '';

    if ($return_id && in_array($action, ['approve', 'reject'])) {
        $new_status = ($action === 'approve') ? 'Approved' : 'Rejected';

        $stmt = $conn->prepare("UPDATE returns SET status = ? WHERE return_id = ?");
        $stmt->bind_param("ss", $new_status, $return_id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();

    // Redirect back to sales_returns.php
    header("Location: sales_returns.php");
    exit();
} else {
    header("Location: sales_returns.php");
    exit();
}
