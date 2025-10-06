<?php
if ($argc < 2) {
  echo "Usage: php generate_hash.php your_password\n";
  exit(1);
}
echo password_hash($argv[1], PASSWORD_DEFAULT) . PHP_EOL;
