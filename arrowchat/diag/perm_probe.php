<?php
// /arrowchat/diag/perm_probe.php
// Purpose: check if cache directories are writable (ArrowChat needs write perms).
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);
$paths = [
  $root . '/cache',
  $root . '/cache/chatroom',
  $root . '/cache/home',
  $root . '/cache/avatars',
];

foreach ($paths as $p) {
  echo $p . " : " . (is_dir($p) ? 'DIR' : 'MISSING') . " : writable=" . (is_writable($p) ? 'yes' : 'no') . "\n";
  if (is_dir($p) && is_writable($p)) {
    $test = $p . '/write_test_' . uniqid() . '.tmp';
    $ok = @file_put_contents($test, 'ok');
    echo "  write_test=" . ($ok !== false ? "ok ($test)" : "FAILED") . "\n";
    if ($ok !== false) @unlink($test);
  }
}
?>