<?php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $conn->query("UPDATE return_requests SET status='Rejected' WHERE id = $id");
}

header("Location: notifications.php?msg=return_rejected");
exit;
