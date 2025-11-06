<?php
// /arrowchat/diag/diag_external.php
// Purpose: run external.php with verbose error reporting and show any fatal errors/output.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/plain; charset=utf-8');

echo "== ArrowChat external.php diagnostic ==\n";

$root = dirname(__DIR__);
$external = $root . "/external.php";
if (!file_exists($external)) {
  echo "ERROR: external.php not found at $external\n";
  exit;
}

echo "Including: $external\n";
ob_start();
include $external;
$out = ob_get_clean();

echo "\n-- Output from external.php (first 500 chars) --\n";
if (strlen($out) === 0) {
  echo "[no output]\n";
} else {
  echo substr($out, 0, 500), "\n";
}

$err = error_get_last();
echo "\n-- error_get_last() --\n";
var_export($err);
echo "\n";

// Also dump PHP version and key INI
echo "\n-- PHP info (brief) --\n";
echo "PHP_VERSION=" . PHP_VERSION . "\n";
echo "display_errors=" . ini_get('display_errors') . "\n";

?>