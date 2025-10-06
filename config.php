<?php
return [
  'db' => [
    'host' => '127.0.0.1',
    'user' => 'root',
    'pass' => '',        // if you set a root password in XAMPP, put it here when copying to config.php
    'name' => 'g17_security'
  ],
  'security' => [
    'max_attempts' => 5,
    'window_minutes' => 15,
    'block_minutes' => 30
  ]
];
