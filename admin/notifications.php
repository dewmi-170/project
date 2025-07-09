<?php
session_start();

// --- DB Connection ---
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "supermarket_inventory";
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Get Logged-in User ID ---
$user_id = $_SESSION['user_id'] ?? 1;

$success = false;

// --- Handle Form Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_prefs'])) {
    $low_stock = isset($_POST['low_stock']) ? 1 : 0;
    $sales_summary = isset($_POST['sales_summary']) ? 1 : 0;
    $return_notify = isset($_POST['return_notify']) ? 1 : 0;
    $supplier_updates = isset($_POST['supplier_updates']) ? 1 : 0;
    $system_maintenance = isset($_POST['system_maintenance']) ? 1 : 0;

    $check = $conn->query("SELECT * FROM notification_preferences WHERE user_id = $user_id");
    if ($check->num_rows > 0) {
        $sql = "UPDATE notification_preferences SET 
                low_stock=$low_stock, 
                sales_summary=$sales_summary, 
                return_notify=$return_notify,
                supplier_updates=$supplier_updates,
                system_maintenance=$system_maintenance 
                WHERE user_id = $user_id";
    } else {
        $sql = "INSERT INTO notification_preferences 
                (user_id, low_stock, sales_summary, return_notify, supplier_updates, system_maintenance)
                VALUES ($user_id, $low_stock, $sales_summary, $return_notify, $supplier_updates, $system_maintenance)";
    }

    if ($conn->query($sql)) {
        $success = true;
    }
}

// --- Load Existing Preferences ---
$prefs = array_fill_keys(['low_stock', 'sales_summary', 'return_notify', 'supplier_updates', 'system_maintenance'], 0);

$res = $conn->query("SELECT * FROM notification_preferences WHERE user_id = $user_id");
if ($res && $res->num_rows > 0) {
    $prefs = $res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Notifications | GreenChoice Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      height: 100%;
      background-color: #2f4f4f;
      padding-top: 20px;
      color: white;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: white;
      text-decoration: none;
      font-size: 16px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }

    .container {
      margin-left: 260px;
      padding: 40px 30px;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 30px;
      background-color: #ffffff;
    }

    .form-check-input:checked {
      background-color: #28a745;
      border-color: #28a745;
    }

    .btn-success {
      background-color: #28a745;
      border-color: #28a745;
    }

    .section-title {
      color: #28a745;
    }

    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1055;
    }

    .top-right-btn {
      position: absolute;
      top: 20px;
      right: 30px;
    }

    .purchase-card {
      background: #fff;
      border-left: 5px solid #007bff;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-shop-window me-2"></i>GreenChoice Admin</h4>
  <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="user_management.php"><i class="bi bi-person-gear me-2"></i> User Management</a>
  <a href="product_management.php"><i class="bi bi-box-seam me-2"></i> Product Management</a>
  <a href="inventory_oversight.php"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php" class="active"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Success Toast for Preferences -->
<?php if ($success): ?>
  <div class="toast-container">
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          ✅ Preferences saved successfully!
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Alert Sent Toast -->
<?php if (isset($_GET['alert']) && $_GET['alert'] === 'sent' && isset($_GET['id'])): ?>
  <div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container">
    <div class="toast align-items-center text-bg-success border-0 show" role="alert">
      <div class="d-flex">
        <div class="toast-body">
          ✅ Alert sent for product ID: <?= htmlspecialchars($_GET['product_id']) ?>.
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
<?php endif; ?>

<div class="container">
  <h2 class="section-title">🔔 Notification Settings</h2>

  
  <div class="card mt-4">
    <form method="post">
      <input type="hidden" name="save_prefs" value="1" />
      <?php
      $options = [
        'low_stock' => ['Receive low stock alerts', 'bi-exclamation-triangle-fill'],
        'sales_summary' => ['Daily sales summary report', 'bi-clipboard-data'],
        'return_notify' => ['Notify return/refund requests', 'bi-arrow-counterclockwise'],
        'supplier_updates' => ['Notify supplier updates', 'bi-truck'],
        'system_maintenance' => ['System updates & maintenance alerts', 'bi-tools'],
      ];
      foreach ($options as $key => [$label, $icon]):
      ?>
        <div class="form-check mb-3">
          <input type="checkbox" class="form-check-input" name="<?= $key ?>" id="<?= $key ?>" <?= $prefs[$key] ? 'checked' : '' ?>>
          <label class="form-check-label" for="<?= $key ?>"><i class="bi <?= $icon ?>"></i> <?= $label ?></label>
        </div>
      <?php endforeach; ?>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-save2"></i> Save Preferences
        </button>
        <a href="admin_dashboard.php" class="btn btn-outline-success">
          <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
        </a>
      </div>
    </form>
  </div>

  <!-- Purchase Request Section -->
  <div class="card purchase-card shadow mt-5">
    <div class="card-body">
      <h5 class="card-title">🛒 Purchase Requests</h5>
      <p class="card-text">Approve or reject supplier purchase requests received by the admin.</p>
      <a href="../admin/admin_purchase_requests.php" class="btn btn-primary">
        <i class="bi bi-check2-square"></i> Manage Requests
      </a>
    </div>
  </div>

  <!-- Return Request Table -->
<div class="card mt-5 border-info shadow">
  <div class="card-body">
    <h5 class="card-title text-info">
      <i class="bi bi-arrow-return-left"></i> Pending Return/Refund Requests
    </h5>
    <p class="card-text">These refund or return requests are pending your approval:</p>

    <?php
    $return_query = $conn->query("
      SELECT r.*, p.product_name, u.username
      FROM return_requests r
      JOIN products p ON r.product_id = p.product_id
      JOIN users u ON r.requested_by = u.id
      WHERE r.status = 'Pending'
      ORDER BY r.created_at DESC
    ");
    ?>

    <?php if ($return_query && $return_query->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-hover table-bordered table-striped align-middle" style="min-width: 1000px;">
          <thead class="table-info text-dark">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Invoice ID</th>
              <th scope="col">Product</th>
              <th scope="col">Quantity</th>
              <th scope="col">Reason</th>
              <th scope="col">Requested By</th>
              <th scope="col">Date</th>
              <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php while ($r = $return_query->fetch_assoc()): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= htmlspecialchars($r['invoice_id']) ?></td>
              <td><?= htmlspecialchars($r['product_name']) ?></td>
              <td><span class="badge bg-warning text-dark"><?= $r['quantity'] ?></span></td>
              <td><?= htmlspecialchars($r['reason']) ?></td>
              <td><?= htmlspecialchars($r['username']) ?></td>
              <td><?= date('Y-m-d H:i', strtotime($r['created_at'])) ?></td>
              <td>
                <div class="d-flex gap-2">
                  <a href="approve_return.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-success">
                    <i class="bi bi-check-circle-fill"></i> Approve
                  </a>
                  <a href="reject_return.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-danger">
                    <i class="bi bi-x-circle-fill"></i> Reject
                  </a>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-secondary text-center">
        <i class="bi bi-info-circle"></i> No pending return/refund requests at this moment.
      </div>
    <?php endif; ?>
  </div>
</div>


  <!-- Low Stock Section -->
  <div class="card mt-5 border-danger">
    <div class="card-body">
      <h5 class="card-title text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Low Stock Items</h5>
      <p class="card-text">The following products are running low in stock (≤ 5 units):</p>
      <form method="post" action="handle_alert_action.php">
        <table class="table table-bordered table-striped">
          <thead class="table-light">
            <tr>
              <th>Product ID</th>
              <th>Name</th>
              <th>Category</th>
              <th>Stock Qty</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php
          $low_result = $conn->query("SELECT product_id, product_name, category, stock_qty FROM products WHERE stock_qty <= 5 ORDER BY stock_qty ASC");
          if ($low_result && $low_result->num_rows > 0):
              while ($row = $low_result->fetch_assoc()):
          ?>
              <tr>
                <td><?= $row['product_id'] ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['category']) ?></td>
                <td><span class="badge bg-danger"><?= $row['stock_qty'] ?></span></td>
                <td>
                  <button type="submit" name="send_alert" value="<?= $row['product_id'] ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-bell-fill"></i> Alert
                  </button>
                </td>
              </tr>
          <?php endwhile; else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No low stock items.</td>
              </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
