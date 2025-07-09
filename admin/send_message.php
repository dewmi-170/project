<?php
include '../db_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $sender = $_POST['sender_role'];
    $stmt = $conn->prepare("INSERT INTO messages (sender_role, message) VALUES (?, ?)");
    $stmt->bind_param("ss", $sender, $message);
    $stmt->execute();
    header("Location: messaging.php");
    exit();
}
?>
