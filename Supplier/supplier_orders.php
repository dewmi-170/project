<?php include '../db_connect.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Supplier - View Purchase Orders</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-success">📦 Approved Purchase Orders</h2>
      <!-- Back Button -->
      <a href="supplier_dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>

    <?php
    // DB එකෙන් status එක 'Approved' වගේ orders select කරන්න
    $result = $conn->query("SELECT * FROM purchase_requests WHERE status = 'Approved' ORDER BY id DESC");
    ?>

    <table class="table table-bordered bg-white shadow-sm">
      <thead class="table-success">
        <tr>
          <th>PO ID</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Expected Delivery</th>
          <th>Requested Date</th>
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
          <td><?= htmlspecialchars($row['requested_at']) ?></td>
          <td>
            <?php 
              if ($row['status'] == 'Approved' && empty($row['delivered_at'])): 
            ?>
              <form action="mark_delivered.php" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-sm btn-success">Mark as Delivered</button>
              </form>
            <?php 
              elseif (!empty($row['delivered_at'])): 
            ?>
              <span class='badge bg-primary'>Delivered</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
