<?php
require_once __DIR__ . '/../includes/db.php';
$rows = $pdo->query("SELECT * FROM ip_blocks ORDER BY blocked_until DESC")->fetchAll();
?>
<!doctype html>
<html><body>
<h2>Blocked IPs</h2>
<table border="1" cellpadding="6">
  <tr><th>IP</th><th>Blocked From</th><th>Blocked Until</th><th>Reason</th><th>Action</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?php echo htmlspecialchars($r['ip']); ?></td>
      <td><?php echo $r['blocked_from']; ?></td>
      <td><?php echo $r['blocked_until']; ?></td>
      <td><?php echo htmlspecialchars($r['reason']); ?></td>
      <td><form method="post" action="unblock.php"><input type="hidden" name="ip" value="<?php echo $r['ip']; ?>"><button>Unblock</button></form></td>
    </tr>
  <?php endforeach; ?>
</table>
</body></html>
