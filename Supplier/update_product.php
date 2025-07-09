<?php
// DB Connection
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and sanitize inputs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $price = floatval($_POST['price']);
    $availability = $conn->real_escape_string(trim($_POST['availability']));
    $lead_time = intval($_POST['lead_time']);

    // Basic validation
    if ($product_id <= 0 || $price <= 0 || $lead_time < 0 || empty($availability)) {
        die("Invalid input provided.");
    }

    // Update the product
    $update_sql = "UPDATE supplier_products 
                   SET price = ?, availability = ?, lead_time = ?
                   WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("dsii", $price, $availability, $lead_time, $product_id);

    if ($stmt->execute()) {
        header("Location: manage_products.php?success=1");
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}
?>
