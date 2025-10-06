<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
$config = require __DIR__ . '/../config.php';
$max = (int)$config['security']['max_attempts'];
$window = (int)$config['security']['window_minutes'];
$blockMinutes = (int)$config['security']['block_minutes'];

$ip = get_client_ip();
if ($blocked = is_ip_blocked($pdo, $ip)) {
  $until = $blocked['blocked_until'];
  die("Your IP is blocked until $until. Contact admin.");
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if (!$username || !$password) {
  die("Missing fields.");
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password_hash'])) {
  clear_failed_attempts($pdo, $ip);
  $_SESSION['user_id'] = $user['id'];
  echo "Login successful. Welcome " . htmlspecialchars($username);
} else {
  record_failed_attempt($pdo, $ip, $username);
  $cnt = count_recent_failed_attempts($pdo, $ip, $window);

  if ($cnt >= $max) {
    block_ip($pdo, $ip, $blockMinutes, "Exceeded $max attempts in $window minutes");
    die("Too many failed attempts — your IP has been temporarily blocked for $blockMinutes minutes.");
  } else {
    $left = $max - $cnt;
    die("Login failed. You have $left attempts left before temporary block.");
  }
}
