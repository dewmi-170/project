<?php
session_start();
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");

$sender = $_SESSION['username'] ?? 'supplier';
$recipient = $_POST['recipient'];
$message = $_POST['message'];

if ($conn->connect_error) {
    die("Connection failed.");
}

$sql = "INSERT INTO messages (sender, recipient, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $sender, $recipient, $message);
if ($stmt->execute()) {
    $_SESSION['msg_status'] = 'success';
    $_SESSION['msg_text'] = 'Message sent successfully!';
} else {
    $_SESSION['msg_status'] = 'danger';
    $_SESSION['msg_text'] = 'Failed to send message.';
}
$stmt->close();
$conn->close();
header("Location: supplier_communication.php");
exit;
