<?php
// DB connection
$host = 'localhost';
$dbname = 'supermarket_inventory';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle filter inputs
$category = $_GET['category'] ?? 'All';
$status = $_GET['status'] ?? 'All';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Build SQL query
$sql = "SELECT * FROM products WHERE 1=1";
if ($category !== 'All') {
    $sql .= " AND category = '" . $conn->real_escape_string($category) . "'";
}
if ($status !== 'All') {
    if ($status === 'Low Stock') {
        $sql .= " AND stock_qty <= 5 AND stock_qty > 0";
    } elseif ($status === 'In Stock') {
        $sql .= " AND stock_qty > 5";
    } elseif ($status === 'Out of Stock') {
        $sql .= " AND stock_qty = 0";
    }
}
if (!empty($search)) {
    $sql .= " AND product_name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$count_result = $conn->query($sql);
$total_rows = $count_result->num_rows;
$total_pages = ceil($total_rows / $limit);

$sql .= " LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Summary counts
$summary = [
  'in_stock' => 0,
  'low_stock' => 0,
  'out_stock' => 0
];
$sum_sql = "SELECT stock_qty FROM products";
$sum_result = $conn->query($sum_sql);
if ($sum_result) {
    while ($r = $sum_result->fetch_assoc()) {
        $q = (int)$r['stock_qty'];
        if ($q === 0) $summary['out_stock']++;
        elseif ($q <= 5) $summary['low_stock']++;
        else $summary['in_stock']++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory Monitoring | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .sidebar { width: 250px; background-color: #343a40; height: 100vh; position: fixed; top: 0; left: 0; padding: 20px; color: white; }
    .sidebar h4 { color: #28a745; margin-bottom: 30px; }
    .sidebar a { display: block; color: #ffffff; text-decoration: none; margin: 10px 0; padding: 10px; border-radius: 5px; }
    .sidebar a:hover { background-color: #28a745; }
    .main-content { margin-left: 270px; padding: 30px; }
    .page-header { background-color: #28a745; color: white; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
    .filter-section, .summary-section, .inventory-table { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); margin-bottom: 20px; }
    .badge-low { background-color: #dc3545; }
    .badge-ok { background-color: #28a745; }
    .badge-out { background-color: #ffc107; color: black; }
    .btn-back { float: right; margin-top: 20px; }
    .pagination { justify-content: center; }
  </style>
</head>
<body>
<div class="sidebar">
  <h4><i class="bi bi-box-seam-fill me-2"></i>Stock Manager</h4>
  <a href="stock_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
  <a href="inventory_monitoring.php" class="bg-success"><i class="bi bi-boxes me-2"></i>Inventory Monitoring</a>
  <a href="stock_operations.php"><i class="bi bi-tools me-2"></i>Stock Operations</a>
  <a href="purchasing_oversight.php"><i class="bi bi-cart-check me-2"></i>Purchasing Oversight</a>
  <a href="sales_returns.php"><i class="bi bi-cash-coin me-2"></i>Sales & Returns</a>
  <a href="reports.php"><i class="bi bi-bar-chart-line me-2"></i>Reports</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i>Notifications</a>
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Log Out</a>
</div>

<div class="main-content">
  <div class="page-header">
    <h3><i class="bi bi-boxes me-2"></i>Inventory Monitoring</h3>
  </div>

  <!-- Summary -->
  <div class="summary-section">
    <span class="badge bg-success me-2">🟢 <?= $summary['in_stock'] ?> In Stock</span>
    <span class="badge bg-warning text-dark me-2">🟡 <?= $summary['low_stock'] ?> Low Stock</span>
    <span class="badge bg-danger">🔴 <?= $summary['out_stock'] ?> Out of Stock</span>
  </div>

  <!-- Filters -->
  <div class="container mt-4">
  <h3 class="mb-3">Product Table</h3>

  <!-- Filter + Search Form -->
  <form class="row g-3 mb-4" method="GET">
    <div class="col-md-3">
      <label class="form-label">Category</label>
      <select class="form-select" name="category">
        <option value="All" <?= $category == 'All' ? 'selected' : '' ?>>All</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat) ?>" <?= $category == $cat ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select class="form-select" name="status">
          <option <?= $status == 'All' ? 'selected' : '' ?>>All</option>
          <option <?= $status == 'Low Stock' ? 'selected' : '' ?>>Low Stock</option>
          <option <?= $status == 'In Stock' ? 'selected' : '' ?>>In Stock</option>
          <option <?= $status == 'Out of Stock' ? 'selected' : '' ?>>Out of Stock</option>
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">Search by Product</label>
        <input type="text" class="form-control" name="search" placeholder="e.g. Rice" value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-2 align-self-end">
        <button type="submit" class="btn btn-success w-100">Filter</button>
      </div>
    </form>
  </div>

  <!-- Inventory Table -->
  <div class="inventory-table">
    <div class="d-flex justify-content-between mb-3">
      <h5>Product List</h5>
      <a href="export_inventory.php" class="btn btn-outline-success"><i class="bi bi-file-earmark-arrow-down"></i> Export to CSV</a>
    </div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th>Product</th>
          <th>Category</th>
          <th>Quantity</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><a href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['product_id'] ?>"> <?= htmlspecialchars($row['product_name']) ?></a></td>
              <td><?= htmlspecialchars($row['category']) ?></td>
              <td><?= htmlspecialchars($row['stock_qty']) ?></td>
              <td>
                <?php
                $qty = $row['stock_qty'];
                if ($qty == 0) echo '<span class="badge badge-out">Out of Stock</span>';
                elseif ($qty <= 5) echo '<span class="badge badge-low">Low Stock</span>';
                else echo '<span class="badge badge-ok">In Stock</span>';
                ?>
              </td>
              <td>
                <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i></a>
                <a href="delete_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="bi bi-trash"></i></a>
              </td>
            </tr>

            <!-- Modal -->
            <div class="modal fade" id="viewModal<?= $row['product_id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>Name:</strong> <?= htmlspecialchars($row['product_name']) ?></p>
                    <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                    <p><strong>Quantity:</strong> <?= htmlspecialchars($row['stock_qty']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($row['description'] ?? '-') ?></p>
                  </div>
                </div>
              </div>
            </div>

          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="5" class="text-center">No inventory data found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
      <nav>
        <ul class="pagination">
          <?php for ($p = 1; $p <= $total_pages; $p++): ?>
            <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
              <a class="page-link" href="?category=<?= $category ?>&status=<?= $status ?>&search=<?= $search ?>&page=<?= $p ?>"> <?= $p ?> </a>
            </li>
          <?php endfor; ?>
        </ul>
      </nav>
    <?php endif; ?>

    <a href="stock_dashboard.php" class="btn btn-secondary btn-back">← Back</a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
