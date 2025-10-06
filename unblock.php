<?php
require_once __DIR__ . '/../includes/db.php';
$ip = $_POST['ip'] ?? null;
if ($ip) {
  $stmt = $pdo->prepare("DELETE FROM ip_blocks WHERE ip = ?");
  $stmt->execute([$ip]);
}
header("Location: admin.php");
