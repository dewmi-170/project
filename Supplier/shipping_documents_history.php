<?php
session_start();
// ඔබේ supplier user session check කරන්න
// if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }

$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, supplier ID to fetch only their documents
// $supplier_id = $_SESSION['user_id'];

$sql = "SELECT order_id, carrier_name, tracking_number, file_name, uploaded_at FROM supplier_shipping_documents ORDER BY uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Shipping Documents History | Supplier Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container my-5">
  <h3 class="mb-4">Shipping Documents History</h3>
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
  <a href="shipping_documents.php" class="btn btn-secondary">Back to Upload</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
