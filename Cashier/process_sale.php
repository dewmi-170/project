<?php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cashier = $_POST['cashier_name'];
    $product_ids = $_POST['product_id']; // array
    $quantities = $_POST['quantity']; // array
    $payment_method = $_POST['payment_method'];

    // Generate invoice ID
    $invoice_id = "INV" . date("Ymd") . "-" . rand(100, 999);

    foreach ($product_ids as $index => $product_id) {
        $qty = intval($quantities[$index]);

        // Get price
        $result = $conn->query("SELECT price FROM products WHERE product_id = '$product_id'");
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $price = $row['price'];
            $total = $qty * $price;

            // Insert sale record
            $stmt = $conn->prepare("INSERT INTO sales (invoice_id, cashier_name, product_id, quantity, total_price, sale_date, payment_method) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
            $stmt->bind_param("ssisds", $invoice_id, $cashier, $product_id, $qty, $total, $payment_method);
            $stmt->execute();
            $stmt->close();

            // Update stock
            $conn->query("UPDATE products SET stock_qty = stock_qty - $qty WHERE product_id = '$product_id'");
        }
    }

    // Redirect to invoice print page
    header("Location: print_invoice.php?invoice_id=$invoice_id");
    exit;
} else {
    echo "Invalid request.";
}
?>
