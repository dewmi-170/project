<?php
session_start();
// ඔබේ user session check එකක් දැම්මහොත් හොඳයි
// if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

$message = '';
$message_type = '';

$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['orderId'];
    $carrier = $_POST['carrier'];
    $tracking = $_POST['trackingNo'];
    $file = $_FILES['invoiceFile'];

    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $upload_dir = "uploads/invoices/";
    $new_filename = uniqid("invoice_", true) . "." . $file_ext;

    if (in_array($file_ext, $allowed)) {
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
            $stmt = $conn->prepare("INSERT INTO supplier_shipping_documents (order_id, carrier_name, tracking_number, file_name) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $order_id, $carrier, $tracking, $new_filename);
            if ($stmt->execute()) {
                $message = "✅ Shipping information uploaded successfully!";
                $message_type = "success";
            } else {
                $message = "❌ Database error: " . $stmt->error;
                $message_type = "danger";
            }
            $stmt->close();
        } else {
            $message = "❌ Failed to move uploaded file.";
            $message_type = "danger";
        }
    } else {
        $message = "❌ Invalid file type. Only PDF, JPG, JPEG, PNG allowed.";
        $message_type = "danger";
    }
}

// Fetch history data
$sql = "SELECT order_id, carrier_name, tracking_number, file_name, uploaded_at FROM supplier_shipping_documents ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Shipping Documents | Supplier Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> <!-- Bootstrap Icons -->

  <style>
    body { background-color: #eef6f1; font-family: 'Segoe UI', sans-serif; padding-left: 250px; }
    .sidebar {
      width: 250px; height: 100vh; background-color: #2e7d32; position: fixed; top: 0; left: 0; padding: 30px 20px; color: white;
    }
    .sidebar h4 { font-size: 22px; margin-bottom: 30px; text-align: center; }
    .sidebar a {
      display: flex; align-items: center; gap: 10px; padding: 12px 10px; color: white; text-decoration: none; margin-bottom: 10px; border-radius: 8px;
      transition: background-color 0.3s ease;
    }
    .sidebar a.active, .sidebar a:hover {
      background-color: #1b5e20; font-weight: bold;
    }
    .main-wrapper {
      max-width: 900px; margin: 40px auto 60px auto; background: white; border-radius: 12px; padding: 30px; box-shadow: 0 6px 18px rgb(0 0 0 / 0.1);
    }
    .btn-upload {
      background-color: #007bff; color: white; border: none;
    }
    .btn-upload:hover {
      background-color: #0056b3;
    }
    .form-label i {
      margin-right: 5px; color: #007bff;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php"><i class="bi bi-bag-check-fill"></i> View Orders</a>
  <a href="manage_products.php"><i class="bi bi-tags-fill"></i> Manage Products</a>
  <a href="shipping_documents.php" class="active"><i class="bi bi-truck"></i> Shipping & Docs</a>
  <a href="supplier_communication.php"><i class="bi bi-chat-dots-fill"></i> Communication</a>
  <a href="supplier_invoices.php"><i class="bi bi-receipt-cutoff"></i> Invoice Tracking</a>
  <a href="supplier_notifications.php"><i class="bi bi-bell-fill"></i> Notifications</a>
  <hr />
  <a href="../logout.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main content -->
<div class="main-wrapper">
  <h3><i class="fas fa-truck"></i> Shipping Documents</h3>

  <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
      <?php echo $message; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <!-- Upload form -->
  <form method="post" enctype="multipart/form-data" class="mb-4">
    <div class="mb-3">
      <label for="orderId" class="form-label"><i class="fas fa-receipt"></i> Purchase Order ID</label>
      <input type="text" class="form-control" id="orderId" name="orderId" placeholder="Enter PO ID" required />
    </div>
    <div class="mb-3">
      <label for="carrier" class="form-label"><i class="fas fa-truck-moving"></i> Carrier / Courier Name</label>
      <input type="text" class="form-control" id="carrier" name="carrier" placeholder="e.g., DHL, Aramex" required />
    </div>
    <div class="mb-3">
      <label for="trackingNo" class="form-label"><i class="fas fa-barcode"></i> Tracking Number</label>
      <input type="text" class="form-control" id="trackingNo" name="trackingNo" placeholder="Enter Tracking Number" required />
    </div>
    <div class="mb-3">
      <label for="invoiceFile" class="form-label"><i class="fas fa-file-upload"></i> Upload Invoice</label>
      <input type="file" class="form-control" id="invoiceFile" name="invoiceFile" accept=".pdf,.jpg,.jpeg,.png" required />
    </div>
    <button type="submit" class="btn btn-upload w-100"><i class="fas fa-cloud-upload-alt"></i> Upload Shipping Info</button>
  </form>

  <!-- History Table -->
  <h4>Shipping Documents History</h4>
  <table class="table table-bordered table-hover">
    <thead class="table-success">
      <tr>
        <th>Order ID</th>
        <th>Carrier</th>
        <th>Tracking Number</th>
        <th>Invoice File</th>
        <th>Uploaded Date</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
            <td><?php echo htmlspecialchars($row['carrier_name']); ?></td>
            <td><?php echo htmlspecialchars($row['tracking_number']); ?></td>
            <td>
              <a href="<?php echo 'uploads/invoices/' . urlencode($row['file_name']); ?>" target="_blank" download>
                View / Download
              </a>
            </td>
            <td><?php echo date('Y-m-d H:i', strtotime($row['uploaded_at'])); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-center">No shipping documents found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
