<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['reply_recipient'] = $_POST['recipient'];
    $_SESSION['msg_status'] = 'success';
    $_SESSION['msg_text'] = 'Replying to ' . $_POST['recipient'];
}
header("Location: supplier_communication.php");
exit;
