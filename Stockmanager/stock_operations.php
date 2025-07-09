<?php
require '../db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stock Operations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            padding: 20px;
            color:   #28a745;
            flex-shrink: 0;
        }

        .sidebar h4 {
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            padding: 10px;
            color: #ccc;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .sidebar a:hover, .sidebar a.active {
            background-color:   #28a745;
            color: white;
        }

        .logout-link {
            margin-top: 30px;
        }

        .content {
            flex-grow: 1;
            padding: 20px;
        }
    </style>
</head>
<body>

<!-- ✅ Sidebar -->
<div class="sidebar">
    <h4><i class="bi bi-box-seam-fill me-2"></i>Stock Manager</h4>
    <a href="stock_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="inventory_monitoring.php"><i class="bi bi-boxes me-2"></i>Inventory Monitoring</a>
    <a href="stock_operations.php" class="active"><i class="bi bi-tools me-2"></i>Stock Operations</a>
    <a href="purchasing_oversight.php"><i class="bi bi-cart-check me-2"></i>Purchasing Oversight</a>
    <a href="sales_returns.php"><i class="bi bi-cash-coin me-2"></i>Sales & Returns</a>
    <a href="reports.php"><i class="bi bi-bar-chart-line me-2"></i>Reports</a>
    <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i>Notifications</a>

    <div class="logout-link mt-4">
        <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Log Out</a>
    </div>
</div>

<!-- ✅ Main Content -->
<div class="content">
    <h2 class="mb-3">📦 Stock Operations</h2>

    <!-- 🔙 Back Button – Bottom Right -->
<div class="text-end fixed-bottom me-3 mb-3">
    <a href="stock_dashboard.php" class="btn btn-secondary">
        </i> Back
    </a>
</div>
    <!-- 🔷 Summary Panel -->
    <div class="mb-4">
        <?php
        $in_stock = $conn->query("SELECT COUNT(*) FROM products WHERE stock_qty > 10")->fetch_row()[0];
        $low_stock = $conn->query("SELECT COUNT(*) FROM products WHERE stock_qty <= 10 AND stock_qty > 0")->fetch_row()[0];
        $out_stock = $conn->query("SELECT COUNT(*) FROM products WHERE stock_qty = 0")->fetch_row()[0];
        ?>
        <div class="alert alert-info">
            🟢 In Stock: <strong><?= $in_stock ?></strong> |
            🟡 Low Stock: <strong><?= $low_stock ?></strong> |
            🔴 Out of Stock: <strong><?= $out_stock ?></strong>
        </div>
    </div>

    <!-- 🔍 Filter/Search + Export -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by product name"
                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">🔍 Search</button>
        </div>
        <div class="col-md-2">
            <a href="export_csv.php" class="btn btn-success"> Export CSV</a>
        </div>
        <div class="col-md-2">
            <a href="add_product.php" class="btn btn-outline-success">➕ Add Product</a>
        </div>
    </form>

    <!-- 📋 Product Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Qty</th>
            <th>Price (Rs.)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $limit = 10;
        $page = $_GET['page'] ?? 1;
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? '';
        $like = "%$search%";

        $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ? LIMIT $limit OFFSET $offset");
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()):
            $status = ($row['stock_qty'] == 0) ? '🔴 Out' : (($row['stock_qty'] <= 10) ? '🟡 Low' : '🟢 In');
            ?>
            <tr>
                <td><?= $row['product_id'] ?></td>
                <td><a href="#" class="view-details" data-id="<?= $row['product_id'] ?>"><?= $row['product_name'] ?></a></td>
                <td><?= $row['category'] ?></td>
                <td><?= $row['stock_qty'] ?></td>
                <td><?= number_format($row['price'], 2) ?></td>
                <td><?= $status ?></td>
                <td>
                    <a href="edit_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-warning">✏ Edit</a>
                    <a href="delete_product.php?id=<?= $row['product_id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Are you sure you want to delete this product?')">🗑 Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <!-- 📄 Pagination -->
    <?php
    $count_stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE product_name LIKE ?");
    $count_stmt->bind_param("s", $like);
    $count_stmt->execute();
    $total_rows = $count_stmt->get_result()->fetch_row()[0];
    $total_pages = ceil($total_rows / $limit);
    ?>
    <nav>
        <ul class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<!-- 🔍 Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="modalContent">
            <div class="modal-header">
                <h5 class="modal-title">Product Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- AJAX-loaded content -->
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('.view-details').on('click', function (e) {
            e.preventDefault();
            let productId = $(this).data('id');
            $.get('get_product_details.php', {id: productId}, function (data) {
                $('#modalContent .modal-body').html(data);
                new bootstrap.Modal(document.getElementById('productModal')).show();
            });
        });
    });
</script>
</body>
</html>
