<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}
$supplier_username = $_SESSION['username'] ?? 'supplier';

// DB connection
$conn = new mysqli("localhost", "root", "", "supermarket_inventory");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch previous messages
$sql = "SELECT id, sender, recipient, message, sent_at FROM messages 
        WHERE recipient = ? OR sender = ? 
        ORDER BY sent_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $supplier_username, $supplier_username);
$stmt->execute();
$result = $stmt->get_result();

// If reply recipient is set in session (from reply_message.php), get and clear it
$replyRecipient = $_SESSION['reply_recipient'] ?? '';
if (isset($_SESSION['reply_recipient'])) {
    unset($_SESSION['reply_recipient']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Supplier Communication | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body { background-color: #eef6f1; font-family: 'Segoe UI', sans-serif; }
    .sidebar {
      width: 250px; height: 100vh; background-color: #2e7d32; position: fixed;
      top: 0; left: 0; padding: 30px 20px; color: white;
    }
    .sidebar h4 { font-size: 22px; margin-bottom: 30px; text-align: center; }
    .sidebar a {
      display: block; padding: 12px 10px; color: white; text-decoration: none;
      margin-bottom: 10px; border-radius: 8px; transition: background-color 0.3s ease;
    }
    .sidebar a.active {
  background-color: #1b5e20;
  font-weight: bold;
}

    .sidebar a:hover { background-color: #1b5e20; }
    .container { margin-left: 270px; max-width: 900px; margin-top: 50px; }
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    .card-header {
      background-color: #2e7d32; color: white; font-size: 20px; font-weight: 600;
    }
    .btn-send { background-color: #28a745; color: white; }
    .btn-send:hover { background-color: #218838; }
    .message-box {
      border-left: 4px solid #28a745; background-color: #f8fdf8;
      padding: 15px; margin-bottom: 15px; border-radius: 8px;
    }
    .message-box .sender { font-weight: bold; color: #155724; }
    .message-box .time { font-size: 12px; color: #6c757d; }
    .btn-reply, .btn-delete { font-size: 12px; margin-right: 10px; }
  </style>
</head>
<body>

<?php if (isset($_SESSION['msg_status'])): ?>
  <div class="container mt-3">
    <div class="alert alert-<?php echo $_SESSION['msg_status'] == 'success' ? 'success' : 'danger'; ?>">
      <?php echo $_SESSION['msg_text']; unset($_SESSION['msg_status'], $_SESSION['msg_text']); ?>
    </div>
  </div>
<?php endif; ?>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-box-seam"></i> Supplier Panel</h4>
  <a href="supplier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
  <a href="view_orders.php"><i class="bi bi-bag-check"></i> View Orders</a>
  <a href="manage_products.php"><i class="bi bi-tags"></i> Manage Products & Prices</a>
  <a href="shipping_documents.php"><i class="bi bi-truck"></i> Shipping & Documents</a>
  <a href="supplier_communication.php" class="active"><i class="bi bi-chat-dots"></i> Communication</a> <!-- 👈 ACTIVE HERE -->
  <a href="supplier_invoices.php"><i class="bi bi-receipt"></i> Invoice Tracking</a>
  <a href="supplier_notifications.php"><i class="bi bi-bell"></i> Notifications</a>
  <hr>
  <a href="../logout.php" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main -->
<div class="container">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <span><i class="bi bi-envelope-paper-fill"></i> Communication Panel</span>
      <a href="supplier_dashboard.php" class="btn btn-sm btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form action="send_message.php" method="POST">
        <div class="mb-3">
          <label>Send To</label>
          <select name="recipient" class="form-select" required>
            <option value="">-- Select --</option>
            <option value="admin" <?php if ($replyRecipient == 'admin') echo 'selected'; ?>>Admin</option>
            <option value="stock_manager" <?php if ($replyRecipient == 'stock_manager') echo 'selected'; ?>>Stock Manager</option>
          </select>
        </div>
        <div class="mb-3">
          <label>Your Message</label>
          <textarea name="message" rows="4" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-send"><i class="bi bi-send-fill"></i> Send</button>
      </form>

      <hr>
      <h5>📬 Previous Messages</h5>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="message-box">
            <div class="sender">
              <?php
                if ($row['sender'] == $supplier_username) {
                  echo "To: " . ucfirst($row['recipient']);
                } else {
                  echo "From: " . ucfirst($row['sender']);
                }
              ?>
            </div>
            <div class="time"><?php echo date("Y-m-d h:i A", strtotime($row['sent_at'])); ?></div>
            <div class="text mt-2"><?php echo nl2br(htmlspecialchars($row['message'])); ?></div>
            <form action="supplier_communication.php" method="POST" class="d-inline">
              <input type="hidden" name="recipient" value="<?php echo htmlspecialchars($row['sender']); ?>">
              <input type="hidden" name="reply_to" value="<?php echo $row['id']; ?>">
              <button type="submit" name="reply" class="btn btn-sm btn-outline-success btn-reply">
                <i class="bi bi-reply-fill"></i> Reply
              </button>
            </form>
            <form action="delete_message.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this message?');">
              <input type="hidden" name="msg_id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger btn-delete">
                <i class="bi bi-trash-fill"></i> Delete
              </button>
            </form>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No messages yet.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();

// Handle reply button clicked on this page (POST from the reply form)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    // Save the recipient in session to pre-fill the form
    $_SESSION['reply_recipient'] = $_POST['recipient'] ?? '';
    // Redirect back to self to show form prefilled
    header("Location: supplier_communication.php");
    exit;
}
?>
