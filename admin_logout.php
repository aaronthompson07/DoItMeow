<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php';
require_once 'audit.php';
audit($conn, 'logout', 'admin', null, 'logout');
$_SESSION = []; session_destroy();
header('Location: index.php'); exit;
