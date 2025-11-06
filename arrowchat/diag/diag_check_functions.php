<?php
// /arrowchat/diag/diag_check_functions.php
// Try loading custom integration functions to surface syntax/errors.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

$fn = __DIR__ . '/../includes/integrations/custom/functions.php';
echo "== Check custom functions.php ==\nFile: $fn\n";
if (!file_exists($fn)) {
  echo "functions.php not found.\n";
  exit;
}

echo "Including...\n";
include $fn;
echo "Included OK.\n";

// Call a couple of functions if they exist:
if (function_exists('get_user_id')) {
  echo "get_user_id() exists.\n";
} else {
  echo "get_user_id() MISSING.\n";
}
if (function_exists('get_buddy_list')) {
  echo "get_buddy_list() exists.\n";
} else {
  echo "get_buddy_list() MISSING.\n";
}
?>