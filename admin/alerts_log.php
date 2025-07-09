<?php
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");

$result = $conn->query("SELECT al.*, p.product_name FROM alerts_log al 
                        JOIN products p ON al.product_id = p.product_id
                        ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Alert Logs</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h2>📢 Low Stock Alert Logs</h2>
  <table class="table table-bordered mt-4">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Product</th>
        <th>Message</th>
        <th>User ID</th>
        <th>Time</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['product_name']) ?></td>
        <td><?= htmlspecialchars($row['alert_message']) ?></td>
        <td><?= $row['user_id'] ?></td>
        <td><?= $row['created_at'] ?></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
</body>
</html>
