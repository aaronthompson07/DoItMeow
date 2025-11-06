<?php
require_once 'auth.php'; require_admin();
include 'db.php';
if (file_exists('audit.php')) { include 'audit.php'; }

$id = intval($_POST['id'] ?? 0);
if ($id<=0){ http_response_code(400); exit; }
$conn->query("UPDATE tasks SET deleted_at=NULL WHERE id=$id LIMIT 1");
if (function_exists('audit')) { audit($conn, 'task_restore', 'task', $id, null); }
echo 'ok';
?>