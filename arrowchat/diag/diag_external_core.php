<?php
// /arrowchat/diag/diag_external_core.php
// Runs external.php with type=core and shows any fatal errors/output.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

echo "== ArrowChat external.php?type=core diagnostic ==\n";

$root = dirname(__DIR__);
$external = $root . "/external.php";
if (!file_exists($external)) {
  echo "ERROR: external.php not found at $external\n";
  exit;
}

// simulate GET param
$_GET['type'] = 'core';

ob_start();
include $external;
$out = ob_get_clean();

echo "\n-- Output (first 600 chars) --\n";
if (strlen($out) === 0) echo "[no output]\n";
else echo substr($out, 0, 600), "\n";

$err = error_get_last();
echo "\n-- error_get_last() --\n";
var_export($err);
echo "\n";
?>