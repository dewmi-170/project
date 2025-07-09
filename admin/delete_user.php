<?php
session_start();
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: user_management.php?success=User deleted successfully.");
    } else {
        header("Location: user_management.php?success=Failed to delete user.");
    }

    $stmt->close();
    $conn->close();
}
?>
