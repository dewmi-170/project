<?php
// DB connection
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "supermarket_inventory";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 1) Fetch Sold Items Report
$stock_sql = "
  SELECT 
    p.product_id,
    p.product_name,
    p.category,
    SUM(s.quantity) AS total_sold,
    p.price,
    SUM(s.quantity) * p.price AS total_revenue
  FROM products p
  INNER JOIN sales s ON p.product_id = s.product_id
  WHERE s.status = 'Completed'
  GROUP BY p.product_id, p.product_name, p.category, p.price
  ORDER BY total_sold DESC
";
$stock_result = $conn->query($stock_sql);

// 2) Fetch Sales Summary
$sales_sql = "
  SELECT 
    DATE(sale_date) AS sale_date,
    cashier_name,
    SUM(total_price) AS total_sales
  FROM sales
  WHERE status = 'Completed'
  GROUP BY DATE(sale_date), cashier_name
  ORDER BY sale_date DESC
  LIMIT 50
";
$sales_result = $conn->query($sales_sql);

// 3) Fetch Return Log
$returns_sql = "
  SELECT return_id, return_date, reason, status
  FROM returns
  ORDER BY return_date DESC
  LIMIT 50
";
$returns_result = $conn->query($returns_sql);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Reports | GreenChoice Stock Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; margin:0; }
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      height: 100vh;
      background-color: #343a40;
      color:  #28a745;
      padding: 20px;
      display: flex;
      flex-direction: column;
    }

    .sidebar h4 {
      margin-bottom: 30px;
    }
    .sidebar a { display: block; 
      color: #ffffff; 
      text-decoration: none;
       margin: 5px ;
        padding: 10px;
        border-radius: 5px; }

    .sidebar a:hover, .sidebar a.active {
            background-color:   #28a745;
            color: white;
        }
    .main-content { margin-left:240px; padding:2rem; }
    .btn-back { margin-bottom:1rem; }
    .section { background:#fff; padding:1.5rem; border-radius:.5rem; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:2rem; }
    .section h4 { display:flex; justify-content:space-between; align-items:center; }
    @media print { .sidebar, .btn, .btn-export, .btn-print { display:none !important; } }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4><i class="bi bi-box-seam-fill"></i> Stock Manager</h4>
    <a href="stock_dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="inventory_monitoring.php"><i class="bi bi-boxes me-2"></i>Inventory Monitoring</a>
    <a href="stock_operations.php"><i class="bi bi-tools me-2"></i>Stock Operations</a>
    <a href="purchasing_oversight.php"><i class="bi bi-cart-check me-2"></i>Purchasing Oversight</a>
    <a href="sales_returns.php"><i class="bi bi-cash-coin me-2"></i>Sales & Returns</a>
    <a href="reports.php" class="active"><i class="bi bi-bar-chart-line me-2"></i>Reports</a>
    <a href="notifications.php"><i class="bi bi-bell-fill me-2"></i>Notifications</a>
    <a href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Log Out</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2 class="mb-4">📈 Reports Dashboard</h2>

    <!-- 1) Sold Items Report -->
    <div class="section" id="stock-section">
      <h4>
        🛒 Sold Items Report
        <div>
          <button class="btn btn-sm btn-success btn-export" data-table="stockTable" data-file="sold_items_report.csv">
            <i class="bi bi-file-earmark-arrow-down"></i> Export CSV
          </button>
          <button class="btn btn-sm btn-outline-primary btn-print" data-section="stock-section">
            <i class="bi bi-printer"></i> Print
          </button>
        </div>
      </h4>
      <div class="table-responsive">
        <table id="stockTable" class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr>
              <th>Product ID</th><th>Name</th><th>Category</th>
              <th>Total Sold</th><th>Price (Rs.)</th><th>Revenue (Rs.)</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $sumSold = 0;
              $sumRevenue = 0;
              if ($stock_result->num_rows) {
                while ($r = $stock_result->fetch_assoc()) {
                  $sumSold    += $r['total_sold'];
                  $sumRevenue += $r['total_revenue'];
                  echo "<tr>
                          <td>{$r['product_id']}</td>
                          <td>".htmlspecialchars($r['product_name'])."</td>
                          <td>{$r['category']}</td>
                          <td>{$r['total_sold']}</td>
                          <td>".number_format($r['price'],2)."</td>
                          <td>".number_format($r['total_revenue'],2)."</td>
                        </tr>";
                }
                echo "<tr class='fw-bold table-light'>
                        <td colspan='3'>Totals</td>
                        <td>{$sumSold}</td>
                        <td>—</td>
                        <td>Rs. ".number_format($sumRevenue,2)."</td>
                      </tr>";
              } else {
                echo "<tr><td colspan='6' class='text-center'>No sold items data.</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2) Sales Summary -->
    <div class="section" id="sales-section">
      <h4>
        💰 Sales Summary
        <div>
          <button class="btn btn-sm btn-primary btn-export" data-table="salesTable" data-file="sales_summary.csv">
            <i class="bi bi-file-earmark-arrow-down"></i> Export CSV
          </button>
          <button class="btn btn-sm btn-outline-primary btn-print" data-section="sales-section">
            <i class="bi bi-printer"></i> Print
          </button>
        </div>
      </h4>
      <div class="table-responsive">
        <table id="salesTable" class="table table-bordered table-striped align-middle">
          <thead class="table-dark">
            <tr><th>Date</th><th>Cashier</th><th>Total Sales (Rs.)</th></tr>
          </thead>
          <tbody>
            <?php
              $grand = 0;
              if ($sales_result->num_rows) {
                while ($r = $sales_result->fetch_assoc()) {
                  $grand += $r['total_sales'];
                  echo "<tr>
                          <td>{$r['sale_date']}</td>
                          <td>".htmlspecialchars($r['cashier_name'])."</td>
                          <td>".number_format($r['total_sales'],2)."</td>
                        </tr>";
                }
                echo "<tr class='fw-bold table-light'>
                        <td colspan='2'>Grand Total</td>
                        <td>Rs. ".number_format($grand,2)."</td>
                      </tr>";
              } else {
                echo "<tr><td colspan='3' class='text-center'>No sales data.</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 3) Return Log -->
    <div class="section" id="returns-section">
      <h4>
        ↩️ Return Log
        <div>
          <button class="btn btn-sm btn-outline-secondary btn-export" data-table="returnsTable" data-file="returns_log.csv">
            <i class="bi bi-file-earmark-arrow-down"></i> Export CSV
          </button>
          <button class="btn btn-sm btn-outline-primary btn-print" data-section="returns-section">
            <i class="bi bi-printer"></i> Print
          </button>
        </div>
      </h4>
      <div class="table-responsive">
        <table id="returnsTable" class="table table-bordered table-striped align-middle">
          <thead class="table-light">
            <tr><th>Return ID</th><th>Date</th><th>Reason</th><th>Status</th></tr>
          </thead>
          <tbody>
            <?php
              if ($returns_result->num_rows) {
                while ($r = $returns_result->fetch_assoc()) {
                  $st   = strtolower($r['status']);
                  $badge = $st=='approved'?'success':($st=='rejected'?'danger':'warning');
                  echo "<tr>
                          <td>{$r['return_id']}</td>
                          <td>".substr($r['return_date'],0,10)."</td>
                          <td>".htmlspecialchars($r['reason'])."</td>
                          <td><span class='badge bg-{$badge}'>".htmlspecialchars($r['status'])."</span></td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='4' class='text-center'>No returns data.</td></tr>";
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Back to Dashboard Button - bottom right -->
    <div class="d-flex justify-content-end mt-4">
      <a href="stock_dashboard.php" class="btn btn-outline-secondary">
        </i> Back to Dashboard
      </a>
    </div>
  </div>

  <script>
    // CSV Export
    document.querySelectorAll('.btn-export').forEach(btn=>{
      btn.addEventListener('click',()=>{
        const tbl = document.getElementById(btn.dataset.table);
        const rows = Array.from(tbl.querySelectorAll('tr'));
        let csv = rows.map(r=>{
          return Array.from(r.children)
                      .map(c=>`"${c.innerText.replace(/"/g,'""')}"`)
                      .join(',');
        }).join('\n');
        const blob = new Blob([csv],{type:'text/csv'});
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = btn.dataset.file;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
      });
    });

    // Print Section
    document.querySelectorAll('.btn-print').forEach(btn=>{
      btn.addEventListener('click',()=>{
        const sec = document.getElementById(btn.dataset.section);
        const orig = document.body.innerHTML;
        document.body.innerHTML = sec.outerHTML;
        window.print();
        document.body.innerHTML = orig;
        window.location.reload();
      });
    });
  </script>

</body>
</html>
