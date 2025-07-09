<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'supermarket_inventory'; // <-- change me

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
