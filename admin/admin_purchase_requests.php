<?php include '../db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin - Manage Purchase Requests</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-primary">🛠 Manage Purchase Requests</h2>
      <!-- Back Button -->
      <a href="admin_dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <?php
    // Handle approve/reject actions
    if (isset($_GET['action']) && isset($_GET['id'])) {
      $id = (int)$_GET['id'];
      $action = $_GET['action'] == 'approve' ? 'Approved' : 'Rejected';
      $conn->query("UPDATE purchase_requests SET status='$action' WHERE id=$id");
      echo "<div class='alert alert-success'>Request #$id $action successfully.</div>";
    }

    // Fetch purchase requests from DB
    $result = $conn->query("SELECT * FROM purchase_requests ORDER BY id DESC");
    ?>

    <table class="table table-bordered bg-white shadow-sm">
      <thead class="table-secondary">
        <tr>
          <th>ID</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Expected Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td>#PO<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT) ?></td>
          <td><?= htmlspecialchars($row['product_name']) ?></td>
          <td><?= (int)$row['quantity'] ?></td>
          <td><?= htmlspecialchars($row['expected_date']) ?></td>
          <td>
            <span class='badge 
              <?= $row['status'] == 'Approved' ? 'bg-success' : ($row['status'] == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') ?>'>
              <?= $row['status'] ?>
            </span>
          </td>
          <td>
  <?php if ($row['status'] == 'Pending'): ?>
    <a href="?action=approve&id=<?= $row['id'] ?>" class="btn btn-sm btn-success">Approve</a>
    <a href="?action=reject&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger">Reject</a>
  <?php elseif ($row['status'] == 'Approved' && !empty($row['delivered_at'])): ?>
    <span class='badge bg-primary'>Delivered</span>
  <?php else: ?>
    <em>No Action</em>
  <?php endif; ?>
</td>

        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
