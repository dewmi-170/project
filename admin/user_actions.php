<?php
include 'db_connect.php';

// ADD USER
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, role, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $role, $email, $password);
    $stmt->execute();

    header("Location: user_management.php");
    exit();
}

// DELETE USER
if (isset($_POST['delete_user'])) {
    $delete_id = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    header("Location: user_management.php");
    exit();
}

// UPDATE USER
if (isset($_POST['update_user'])) {
    $id = $_POST['edit_id'];
    $username = $_POST['edit_username'];
    $role = $_POST['edit_role'];
    $email = $_POST['edit_email'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, role = ?, email = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $role, $email, $id);
    $stmt->execute();

    header("Location: user_management.php");
    exit();
}
?>
