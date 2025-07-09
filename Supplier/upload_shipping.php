<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "supermarket_inventory"; // Replace with your DB
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}

// Get form inputs
$order_id = $_POST['orderId'] ?? '';
$carrier = $_POST['carrier'] ?? '';
$tracking = $_POST['trackingNo'] ?? '';
$file = $_FILES['invoiceFile'] ?? null;

// File handling
$upload_dir = 'uploads/invoices/';
$allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];

if ($file && $file['error'] === 0) {
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (in_array($file_ext, $allowed_types)) {
        $new_filename = uniqid('invoice_', true) . '.' . $file_ext;
        $destination = $upload_dir . $new_filename;

        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Insert to DB
            $stmt = $conn->prepare("INSERT INTO supplier_shipping_documents (order_id, carrier_name, tracking_number, file_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $order_id, $carrier, $tracking, $new_filename);

            if ($stmt->execute()) {
                echo "<script>alert('Shipping info uploaded successfully!'); window.location.href='shipping_documents.php';</script>";
            } else {
                echo "DB error: " . $stmt->error;
            }
        } else {
            echo "❌ Failed to move uploaded file.";
        }
    } else {
        echo "❌ Invalid file type. Only PDF, JPG, PNG allowed.";
    }
} else {
    echo "❌ File upload error.";
}

$conn->close();
?>
