<?php
session_start();
include '../db_connect.php';

if (isset($_POST['add_user'])) {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check for duplicate username
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: user_management.php?success=Username already exists.");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        header("Location: user_management.php?success=User added successfully.");
    } else {
        header("Location: user_management.php?success=Failed to add user.");
    }

    $stmt->close();
    $check->close();
    $conn->close();
}
?>
