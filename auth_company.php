
<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

function require_company() {
  if (empty($_SESSION['company_id'])) {
    header('Location: company_login.php');
    exit;
  }
}

function current_company_id() {
  return $_SESSION['company_id'] ?? null;
}

function set_company($company_id, $company_name) {
  $_SESSION['company_id'] = (int)$company_id;
  $_SESSION['company_name'] = $company_name;
}

function clear_company() {
  $_SESSION['company_id'] = null;
  $_SESSION['company_name'] = null;
  unset($_SESSION['company_id'], $_SESSION['company_name']);
}
?>
