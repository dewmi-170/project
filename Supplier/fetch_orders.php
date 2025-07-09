<?php
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all orders, grouped by order_id (since your table stores one row per product, group them)
$sql = "SELECT order_id, supplier_name, order_date, status, 
       GROUP_CONCAT(CONCAT(product_name, ' (Qty: ', quantity, ')') SEPARATOR ', ') AS products,
       SUM(quantity) AS total_quantity
       FROM purchase_orders
       GROUP BY order_id, supplier_name, order_date, status
       ORDER BY order_date DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table class="table table-bordered table-hover align-middle">';
    echo '<thead class="table-success"><tr>
        <th>Order ID</th>
        <th>Supplier</th>
        <th>Order Date</th>
        <th>Status</th>
        <th>Products</th>
        <th>Action</th>
    </tr></thead><tbody>';

    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>'.htmlspecialchars($row['order_id']).'</td>';
        echo '<td>'.htmlspecialchars($row['supplier_name']).'</td>';
        echo '<td>'.htmlspecialchars($row['order_date']).'</td>';
        
        // Status badge
        $status = $row['status'];
        $badge = '';
        if ($status == 'Pending') {
            $badge = '<span class="badge bg-warning text-dark">Pending</span>';
        } elseif ($status == 'Approved') {
            $badge = '<span class="badge bg-success">Approved</span>';
        } elseif ($status == 'Cancelled') {
            $badge = '<span class="badge bg-danger">Cancelled</span>';
        } else {
            $badge = htmlspecialchars($status);
        }
        echo '<td>'.$badge.'</td>';

        // Products list
        echo '<td>'.htmlspecialchars($row['products']).'</td>';

        // Action buttons
        echo '<td>
            <a href="view_order_details.php?order_id='.urlencode($row['order_id']).'" class="btn btn-info btn-sm">View</a> ';
        if ($status == 'Pending') {
            echo '<a href="approve_order.php?order_id='.urlencode($row['order_id']).'" class="btn btn-success btn-sm">Approve</a>
                  <a href="cancel_order.php?order_id='.urlencode($row['order_id']).'" class="btn btn-danger btn-sm">Cancel</a>';
        }
        echo '</td>';

        echo '</tr>';
    }

    echo '</tbody></table>';
} else {
    echo '<p class="text-center text-muted">No orders found.</p>';
}
$conn->close();
?>
