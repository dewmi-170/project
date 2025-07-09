<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../db_connection.php';
$conn = OpenCon();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $status = $_GET['action'] === 'approve' ? 'Approved' : 'Rejected';
    $stmt = $conn->prepare("UPDATE returns SET status=? WHERE return_id=?");
    $stmt->bind_param("si", $status, $_GET['id']);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_returns.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin | Return Approvals</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <h3>Return Approvals Panel</h3>
  <table class="table table-bordered mt-3">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Invoice ID</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Reason</th>
        <th>Date</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT * FROM returns ORDER BY return_date DESC");
      $i = 1;
      while ($row = $result->fetch_assoc()) {
          echo "<tr>
              <td>{$i}</td>
              <td>{$row['invoice_id']}</td>
              <td>{$row['product_name']}</td>
              <td>{$row['quantity']}</td>
              <td>{$row['reason']}</td>
              <td>{$row['return_date']}</td>
              <td><span class='badge bg-" . 
                  ($row['status'] == 'Approved' ? "success" : ($row['status'] == 'Rejected' ? "danger" : "warning text-dark")) . "'>" . 
                  $row['status'] . "</span></td>
              <td>";
          if ($row['status'] == 'Pending') {
              echo "<a href='?action=approve&id={$row['return_id']}' class='btn btn-success btn-sm me-1'>Approve</a>
                    <a href='?action=reject&id={$row['return_id']}' class='btn btn-danger btn-sm'>Reject</a>";
          } else {
              echo "-";
          }
          echo "</td></tr>";
          $i++;
      }
      $conn->close();
      ?>
    </tbody>
  </table>
</body>
</html>
