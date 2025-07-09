<?php
include '../config/db.php'; // ඔබේ database connection එක

$sql = "SELECT * FROM alerts ORDER BY timestamp DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="alert alert-' . htmlspecialchars($row['type']) . ' alert-box" role="alert">';
        echo '<div class="alert-header">' . htmlspecialchars($row['title']) . '</div>';
        echo '<div>' . htmlspecialchars($row['message']) . '</div>';
        echo '<div class="timestamp">' . date("Y-m-d h:i A", strtotime($row['timestamp'])) . '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="alert alert-secondary alert-box" role="alert">No system alerts found.</div>';
}
$conn->close();
?>
