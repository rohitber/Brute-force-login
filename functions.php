<?php
require_once __DIR__ . '/db.php';

function get_client_ip() {
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
  return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function is_ip_blocked($pdo, $ip) {
  $now = date('Y-m-d H:i:s');
  $stmt = $pdo->prepare("SELECT * FROM ip_blocks WHERE ip = ? AND blocked_until > ?");
  $stmt->execute([$ip, $now]);
  return $stmt->fetch();
}

function record_failed_attempt($pdo, $ip, $username=null) {
  $stmt = $pdo->prepare("INSERT INTO failed_attempts (ip, attempt_time, username_tried) VALUES (?, ?, ?)");
  $stmt->execute([$ip, date('Y-m-d H:i:s'), $username]);
}

function clear_failed_attempts($pdo, $ip) {
  $stmt = $pdo->prepare("DELETE FROM failed_attempts WHERE ip = ?");
  $stmt->execute([$ip]);
}

function count_recent_failed_attempts($pdo, $ip, $minutes) {
  $since = date('Y-m-d H:i:s', strtotime("-{$minutes} minutes"));
  $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM failed_attempts WHERE ip = ? AND attempt_time >= ?");
  $stmt->execute([$ip, $since]);
  return (int)$stmt->fetchColumn();
}

function block_ip($pdo, $ip, $minutes, $reason = null) {
  $from = date('Y-m-d H:i:s');
  $until = date('Y-m-d H:i:s', strtotime("+{$minutes} minutes"));
  $stmt = $pdo->prepare("REPLACE INTO ip_blocks (ip, blocked_from, blocked_until, reason) VALUES (?, ?, ?, ?)");
  $stmt->execute([$ip, $from, $until, $reason]);
}
