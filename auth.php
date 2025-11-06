<?php
// auth.php — shared admin auth utilities
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function is_admin_logged_in(){
  return !empty($_SESSION['is_admin']) && !empty($_SESSION['admin_user_id']);
}

function require_admin(){
  if (!is_admin_logged_in()){
    header('Location: admin_login.php?next=' . urlencode($_SERVER['REQUEST_URI'] ?? 'admin_dashboard.php'));
    exit;
  }
}
?>