<?php
session_start();
include 'db_connection.php'; // Database connection file එක include කරන්න (oya use karana file එක)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role = $_POST['role'];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($role) || empty($username) || empty($password)) {
        header("Location: login.php?error=Please fill all fields");
        exit();
    }

    // Prepare and execute query
    $conn = OpenCon(); // db_connection.php file එකේ function එකක් කියලා assume කරනවා

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = ?");
    $stmt->bind_param("ss", $username, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Password verify
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($user['role'] == 'stock_manager') {
                header("Location: stock_manager_dashboard.php");
                exit();
            } elseif ($user['role'] == 'cashier') {
                header("Location: cashier_dashboard.php");
                exit();
            } elseif ($user['role'] == 'supplier') {
                header("Location: supplier_dashboard.php");
                exit();
            } else {
                // Unknown role
                header("Location: login.php?error=Unknown role");
                exit();
            }
        } else {
            // Wrong password
            header("Location: login.php?error=Incorrect password");
            exit();
        }
    } else {
        // No user found
        header("Location: login.php?error=User not found");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If not POST request
    header("Location: login.php");
    exit();
}
?>
