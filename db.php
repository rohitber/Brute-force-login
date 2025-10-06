<?php
$config = require __DIR__ . '/../config.php';
$dbcfg = $config['db'];

$dsn = "mysql:host={$dbcfg['host']};dbname={$dbcfg['name']};charset=utf8mb4";
$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $dbcfg['user'], $dbcfg['pass'], $options);
} catch (PDOException $e) {
  die('DB connect error: ' . $e->getMessage());
}
