<?php include '../db_connect.php'; ?>
<form action="send_message.php" method="POST">
  <textarea name="message" required class="form-control mb-2"></textarea>
  <input type="hidden" name="sender_role" value="<?= $role ?>"> <!-- Admin/Supplier -->
  <button type="submit" class="btn btn-success">Send</button>
</form>

<hr>
<?php
$result = $conn->query("SELECT * FROM messages ORDER BY sent_at DESC");
while ($msg = $result->fetch_assoc()) {
  echo "<p><strong>{$msg['sender_role']}:</strong> {$msg['message']} <small class='text-muted'>({$msg['sent_at']})</small></p>";
}
?>
