<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: ../login.php");
    exit;
}

// DB connection
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_db_user';
$password = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Get supplier ID from session (adjust if your session variable is different)
$supplier_id = $_SESSION['user_id'] ?? 0;

// Fetch notifications for this supplier (latest 20)
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE supplier_id = ? ORDER BY created_at DESC LIMIT 20");
$stmt->execute([$supplier_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- (Include your head content from the previous code above here) -->
</head>
<body>

<!-- Sidebar code here -->

<div class="container-main">
  <div class="card">
    <div class="card-header">
      <span><i class="bi bi-bell-fill"></i> Notifications</span>
      <a href="supplier_dashboard.php" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back
      </a>
    </div>
    <div class="card-body">

      <?php if (count($notifications) > 0): ?>
        <?php foreach ($notifications as $note): ?>
          <div class="notification-item <?php echo ($note['is_read'] == 0) ? 'unread' : ''; ?>">
            <i class="bi <?php echo htmlspecialchars($note['icon'] ?: 'bi-info-circle'); ?>"></i>
            <div class="notification-text">
              <strong><?php echo htmlspecialchars($note['title']); ?></strong><br />
              <?php echo nl2br(htmlspecialchars($note['message'])); ?>
              <div class="notification-time">
                <?php
                $dt = new DateTime($note['created_at']);
                echo $dt->format('M d, Y, h:i A');
                ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="notification-empty">
          <i class="bi bi-bell-slash fs-1"></i>
          <p>No notifications at the moment.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

</body>
</html>
