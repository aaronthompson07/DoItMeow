<?php
require_once 'auth_company.php';
require_once 'user_auth.php';
require_company();
$u = current_user();
if (!$u || !$u['is_admin']) {
  header('Location: index.php?admin_required=1');
  exit;
}
?>