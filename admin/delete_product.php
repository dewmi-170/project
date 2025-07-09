<?php
session_start();
include '../db_connect.php';

// Role validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  header("Location: ../login.php");
  exit;
}

$product_id = $_GET['id'];

$sql = "DELETE FROM products WHERE product_id = '$product_id'";

if ($conn->query($sql) === TRUE) {
  header("Location: product_management.php");
  exit;
} else {
  echo "Error: " . $conn->error;
}
