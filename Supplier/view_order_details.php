<?php
include '../db_connect.php';

if (!isset($_GET['order_id']) || empty(trim($_GET['order_id']))) {
    echo "Invalid Request ID.";
    exit;
}

$order_id = $conn->real_escape_string(trim($_GET['order_id']));

// Get order details
$order_sql = "SELECT * FROM purchase_orders WHERE order_id = '$order_id'";
$order_result = $conn->query($order_sql);

if ($order_result->num_rows == 0) {
    echo "Order not found.";
    exit;
}

$order = $order_result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Order Details - <?= htmlspecialchars($order['order_id']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
  <h2>Order Details - Order #<?= htmlspecialchars($order['order_id']) ?></h2>
  <p><strong>Supplier:</strong> <?= htmlspecialchars($order['supplier_name']) ?></p>
  <p><strong>Order Date:</strong> <?= htmlspecialchars($order['order_date']) ?></p>
  <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>

  <hr>

  <h4>Items</h4>
  <?php
  // Get items of this order from purchase_order_items table
  $items_sql = "SELECT product_name, quantity, unit_price FROM purchase_order_items WHERE order_id = '$order_id'";
  $items_result = $conn->query($items_sql);

  if ($items_result->num_rows == 0) {
      echo "<p>No items found for this order.</p>";
  } else {
      echo '<table class="table table-bordered">';
      echo '<thead><tr><th>#</th><th>Product Name</th><th>Quantity</th><th>Unit Price (Rs.)</th><th>Total Price (Rs.)</th></tr></thead><tbody>';

      $count = 1;
      while ($item = $items_result->fetch_assoc()) {
          $total_price = $item['quantity'] * $item['unit_price'];
          echo "<tr>";
          echo "<td>{$count}</td>";
          echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
          echo "<td>" . (int)$item['quantity'] . "</td>";
          echo "<td>" . number_format($item['unit_price'], 2) . "</td>";
          echo "<td>" . number_format($total_price, 2) . "</td>";
          echo "</tr>";
          $count++;
      }
      echo '</tbody></table>';
  }
  ?>
</div>

</body>
</html>
