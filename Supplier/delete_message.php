<?php
session_start();
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed.");
}
$id = $_POST['msg_id'];
$sql = "DELETE FROM messages WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    $_SESSION['msg_status'] = 'success';
    $_SESSION['msg_text'] = 'Message deleted.';
} else {
    $_SESSION['msg_status'] = 'danger';
    $_SESSION['msg_text'] = 'Failed to delete.';
}
$stmt->close();
$conn->close();
header("Location: supplier_communication.php");
exit;
