<?php
include '../db_connect.php'; 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Stock View | Cashier Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      display: flex;
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f0fdf4;
      height: 100vh;
      overflow: hidden;
    }
    .sidebar {
      width: 250px;
      background-color: #256029;
      color: #fff;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 20px;
      position: fixed;
      height: 100vh;
    }
    .sidebar h4 {
      text-align: center;
      font-size: 1.6rem;
      font-weight: bold;
      margin-bottom: 40px;
    }
    .sidebar a {
      display: flex;
      align-items: center;
      text-decoration: none;
      color: #fff;
      padding: 12px 15px;
      margin-bottom: 12px;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    .sidebar a i {
      margin-right: 10px;
      font-size: 1.3rem;
    }
    .sidebar a:hover,
    .sidebar a.active {
      background-color: #1a4f20;
      padding-left: 20px;
    }
    .logout-btn {
      background-color: #c62828;
      color: #fff;
      font-weight: bold;
      text-align: center;
      padding: 10px 0;
      border-radius: 8px;
      transition: background-color 0.3s ease;
      text-decoration: none;
      margin-top: 40px;
    }
    .logout-btn:hover {
      background-color: #b71c1c;
    }
    .main-content {
      margin-left: 270px;
      padding: 30px;
      width: 100%;
      overflow-y: auto;
      height: 100vh;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .status-instock {
      color: green;
      font-weight: bold;
    }
    .status-outofstock {
      color: red;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="wrapper">
  <div class="sidebar">
    <div>
      <h4><i class="bi bi-person-badge"></i> Cashier Panel</h4>
      <a href="cashier_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="sales_billing.php"><i class="bi bi-credit-card"></i> Sales & Billing</a>
      <a href="stock_view.php" class="active"><i class="bi bi-search"></i> Stock View</a>
      <a href="returns_refunds.php"><i class="bi bi-arrow-return-left"></i> Returns & Refunds</a>
      <a href="sales_reports.php"><i class="bi bi-file-earmark-bar-graph"></i> Reports</a>
      <a href="alerts.php"><i class="bi bi-bell"></i> Alerts</a>
    </div>
    <a href="../logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </div>

  <div class="main-content">
    <div class="card p-4">
      <h3 class="text-center mb-4"><i class="bi bi-boxes me-2"></i>Stock Availability</h3>

      <!-- Search input -->
      <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search products by name or ID...">
      </div>

      <!-- Stock table -->
      <table class="table table-bordered table-striped" id="stockTable">
        <thead class="table-success">
          <tr>
            <th>Product ID</th>
            <th>Item Name</th>
            <th>Available Quantity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="stockTableBody">
          <?php
            $query = "SELECT product_id, product_name, stock_qty FROM products ORDER BY product_name ASC";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['product_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stock_qty']) . "</td>";

                    if ($row['stock_qty'] > 0) {
                        echo "<td class='status-instock'>In Stock</td>";
                    } else {
                        echo "<td class='status-outofstock'>Out of Stock</td>";
                    }

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' class='text-center'>No products found.</td></tr>";
            }
            $conn->close();
          ?>
        </tbody>
      </table>

      <div class="text-center mt-3">
        <a href="cashier_dashboard.php" class="btn btn-outline-success">
          <i class="bi bi-arrow-left-circle me-1"></i>Back to Dashboard
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  const searchInput = document.getElementById('searchInput');
  const tableBody = document.getElementById('stockTableBody');

  searchInput.addEventListener('input', () => {
    const searchTerm = searchInput.value.trim();

    // Send AJAX request to get filtered data
    fetch('search_products.php?query=' + encodeURIComponent(searchTerm))
      .then(response => response.json())
      .then(data => {
        tableBody.innerHTML = '';

        if (data.length > 0) {
          data.forEach(product => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
              <td>${product.product_id}</td>
              <td>${product.product_name}</td>
              <td>${product.stock_qty}</td>
              <td class="${product.stock_qty > 0 ? 'status-instock' : 'status-outofstock'}">
                ${product.stock_qty > 0 ? 'In Stock' : 'Out of Stock'}
              </td>
            `;

            tableBody.appendChild(tr);
          });
        } else {
          tableBody.innerHTML = '<tr><td colspan="4" class="text-center">No products found.</td></tr>';
        }
      })
      .catch(err => {
        console.error('Search error:', err);
      });
  });
</script>

</body>
</html>
