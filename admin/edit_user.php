<?php
session_start();
include '../db_connect.php';

if (isset($_POST['edit_user'])) {
    $id = $_POST['edit_id'];
    $username = trim($_POST['edit_username']);
    $role = $_POST['edit_role'];
    $password = !empty($_POST['edit_password']) ? password_hash($_POST['edit_password'], PASSWORD_DEFAULT) : null;

    // Check if new username already exists for another user
    $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $check_stmt->bind_param("si", $username, $id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: user_management.php?success=Username already exists.");
        exit;
    }

    if ($password) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $password, $role, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $role, $id);
    }

    if ($stmt->execute()) {
        header("Location: user_management.php?success=User updated successfully.");
    } else {
        header("Location: user_management.php?success=Failed to update user.");
    }

    $stmt->close();
    $check_stmt->close();
    $conn->close();
}
?>
