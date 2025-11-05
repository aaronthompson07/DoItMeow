<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
define('ADMIN_PASSWORD', 'admin123'); // change me
function require_admin() {
  if (empty($_SESSION['is_admin'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header("Location: admin_login.php?redirect=$redirect");
    exit;
  }
}
?>
