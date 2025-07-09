<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $invoice_id = $conn->real_escape_string($_POST['invoice_id']);
    $invoice_date = $conn->real_escape_string($_POST['invoice_date']);
    $amount = $conn->real_escape_string($_POST['amount']);
    $status = $conn->real_escape_string($_POST['status']);
    $due_date = $conn->real_escape_string($_POST['due_date']);
    $supplier_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO supplier_invoices (invoice_id, supplier_id, invoice_date, amount, status, due_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdss", $invoice_id, $supplier_id, $invoice_date, $amount, $status, $due_date);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Invoice added successfully!";
    } else {
        $_SESSION['message'] = "Error adding invoice: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();

    header("Location: supplier_invoices.php");
    exit;
} else {
    header("Location: supplier_invoices.php");
    exit;
}
