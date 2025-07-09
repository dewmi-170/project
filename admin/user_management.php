<?php
session_start();
include '../db_connect.php';

// Role validation
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Management | GreenChoice Market</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f7f6;
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
      z-index: 1000;
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
      transition: 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1e3932;
      border-left: 4px solid #00c851;
      color: #00c851;
    }

    .container {
      margin-left: 230px;
      padding: 40px 40px;
    }

    .btn-add {
      background-color: #28a745;
      color: white;
    }

    .btn-add:hover {
      background-color: #218838;
    }

    .page-title {
      margin-bottom: 30px;
    }

    .action-btns .btn {
      margin-right: 5px;
    }

    .back-btn-fixed {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 999;
    }

    .back-btn {
      background-color: #28a745;
      color: white;
      padding: 10px 18px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      border-radius: 50px;
      box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .back-btn:hover {
      background-color: #218838;
      color: white;
    }

    .table thead {
      background-color: #28a745;
      color: white;
    }

    .table {
      background-color: white;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h4><i class="bi bi-shop-window me-2"></i>GreenChoice Admin</h4>
  <a href="admin_dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
  <a href="user_management.php" class="active"><i class="bi bi-person-gear me-2"></i> User Management</a>
  <a href="product_management.php"><i class="bi bi-box-seam me-2"></i> Product Management</a>
  <a href="inventory_oversight.php"><i class="bi bi-graph-up-arrow me-2"></i> Inventory Oversight</a>
  <a href="sales_return.php"><i class="bi bi-currency-dollar me-2"></i> Sales & Returns</a>
  <a href="reports_analytics.php"><i class="bi bi-bar-chart-fill me-2"></i> Reports & Analytics</a>
  <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i> Notifications</a>
  <hr />
  <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="container">
  <div class="d-flex justify-content-between align-items-center page-title">
    <h2>User Management</h2>
    <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
      <i class="bi bi-person-plus-fill"></i> Add New User
    </button>
  </div>

  <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
  <?php endif; ?>

  <table class="table table-bordered table-hover shadow-sm">
    <thead>
      <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['username']) ?></td>
          <td><?= htmlspecialchars($row['role']) ?></td>
          <td class="action-btns">
            <button class="btn btn-sm btn-warning editBtn"
                    data-id="<?= $row['id'] ?>"
                    data-username="<?= $row['username'] ?>"
                    data-role="<?= $row['role'] ?>"
                    data-bs-toggle="modal" data-bs-target="#editUserModal">
              <i class="bi bi-pencil-square"></i>
            </button>
            <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
               onclick="return confirm('Are you sure you want to delete this user?');">
              <i class="bi bi-trash"></i>
            </a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Fixed Back Button -->
<div class="back-btn-fixed">
  <a href="admin_dashboard.php" class="back-btn">
    <i class="bi bi-arrow-left-circle"></i> Back to Dashboard
  </a>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="edit_user.php" method="POST" class="modal-content p-4">
  <h5 class="modal-title mb-3">Edit User</h5>
  
  <!-- Hidden flag to trigger POST handling -->
  <input type="hidden" name="edit_user" value="1">
  
  <input type="hidden" name="edit_id" id="edit_id">

  <div class="mb-3">
    <label for="edit_username" class="form-label">Username</label>
    <input type="text" class="form-control" name="edit_username" id="edit_username" required>
  </div>

  <div class="mb-3">
    <label for="edit_password" class="form-label">New Password (optional)</label>
    <input type="password" class="form-control" name="edit_password" id="edit_password">
  </div>

  <div class="mb-3">
    <label for="edit_role" class="form-label">Role</label>
    <select class="form-select" name="edit_role" id="edit_role" required>
      <option value="admin">Admin</option>
      <option value="stock_manager">Stock Manager</option>
      <option value="cashier">Cashier</option>
      <option value="supplier">Supplier</option>
    </select>
  </div>

  <div class="text-end">
    <button type="submit" class="btn btn-success">Update User</button>
  </div>
</form>


  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="edit_user.php" method="POST" class="modal-content p-4">
      <h5 class="modal-title mb-3">Edit User</h5>
      <input type="hidden" name="id" id="edit_id">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" name="username" id="edit_username" required>
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select" name="role" id="edit_role" required>
          <option value="admin">Admin</option>
          <option value="stock_manager">Stock Manager</option>
          <option value="cashier">Cashier</option>
          <option value="supplier">Supplier</option>
        </select>
      </div>
      <div class="text-end">
        <button type="submit" class="btn btn-success">Update User</button>
      </div>
    </form>
  </div>
</div>

<script>
  const editBtns = document.querySelectorAll('.editBtn');
  editBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('edit_id').value = btn.dataset.id;
      document.getElementById('edit_username').value = btn.dataset.username;
      document.getElementById('edit_role').value = btn.dataset.role;
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
