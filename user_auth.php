<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function current_user() {
  if (empty($_SESSION['user'])) return null;
  return $_SESSION['user'];
}

function set_user($id, $first, $last, $is_admin) {
  $_SESSION['user'] = [
    'id' => (int)$id,
    'first_name' => $first,
    'last_name' => $last,
    'is_admin' => (int)$is_admin
  ];
}

function clear_user() {
  unset($_SESSION['user']);
}

function require_user() {
  if (empty($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'USER_NOT_AUTH']);
    exit;
  }
}

function require_admin_user() {
  if (empty($_SESSION['user']) || empty($_SESSION['user']['is_admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'ADMIN_ONLY']);
    exit;
  }
}
?>