<?php
include '../db_connect.php';

if (!isset($_GET['invoice_id'])) {
    die("Invoice ID not provided.");
}

$invoice_id = $_GET['invoice_id'];

// Get all sales for the invoice
$result = $conn->query("SELECT * FROM sales WHERE invoice_id = '$invoice_id'");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_id = $row['product_id'];
        $qty = $row['quantity'];

        // Restore stock
        $conn->query("UPDATE products SET stock_qty = stock_qty + $qty WHERE product_id = '$product_id'");
        
        // Optionally update sale as returned (only if you have a column like 'status')
        $conn->query("UPDATE sales SET status = 'Returned' WHERE sale_id = '{$row['sale_id']}'");
    }

    // Show confirmation message or redirect
    echo "<script>
        alert('Items for invoice $invoice_id have been marked as returned and stock restored.');
        window.location.href = 'reprint_invoice.php'; // or back to invoice list
    </script>";
} else {
    echo "No records found for this invoice.";
}
?>
