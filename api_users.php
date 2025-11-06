<?php
include 'db.php';
if (file_exists('audit.php')) { include 'audit.php'; }
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

function out($d){ echo json_encode($d); exit; }
function require_admin_json(){
  if (session_status()===PHP_SESSION_NONE) session_start();
  if (empty($_SESSION['is_admin'])) { http_response_code(403); echo json_encode(['error'=>'admin required']); exit; }
}
function validate_required_pin($pin){
  return preg_match('/^[0-9]{6}$/', $pin ?? '') === 1;
}

if ($action === 'list') {
  require_admin_json();
  $include_deleted = isset($_GET['include_deleted']) ? (int)$_GET['include_deleted'] : 1;
  $where = $include_deleted ? "1=1" : "deleted_at IS NULL";
  $sql = "SELECT id, first_name, last_name, pin, CAST(disabled AS UNSIGNED) AS disabled, CAST(is_admin AS UNSIGNED) AS is_admin, deleted_at,
                 TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) AS full_name,
                 name
          FROM users
          WHERE $where
          ORDER BY disabled ASC, deleted_at IS NULL DESC, last_name, first_name, name";
  $res = $conn->query($sql);
  $rows = [];
  while($r = $res->fetch_assoc()){ $rows[] = $r; }
  out($rows);
}

if ($action === 'create' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_admin_json();
  $first = $conn->real_escape_string($_POST['first_name'] ?? '');
  $last  = $conn->real_escape_string($_POST['last_name'] ?? '');
  $pin   = $_POST['pin'] ?? '';
  $is_admin = isset($_POST['is_admin']) ? (intval($_POST['is_admin']) ? 1 : 0) : 0;
  if ($first==='' || $last==='') { http_response_code(400); out(['error'=>'first/last required']); }
  if (!validate_required_pin($pin)) { http_response_code(400); out(['error'=>'PIN must be exactly 6 digits']); }
  $pin_e = $conn->real_escape_string($pin);
  $name  = trim($first.' '.$last);
  $sql = "INSERT INTO users(first_name,last_name,pin,is_admin,disabled,deleted_at,name) VALUES('$first','$last','$pin_e',$is_admin,0,NULL,'$name')";
  if (!$conn->query($sql)) { http_response_code(500); out(['error'=>$conn->error]); }
  $id = $conn->insert_id;
  if (function_exists('audit')) audit($conn,'user_create','user',$id,"name=$name;pin=$pin;is_admin=$is_admin");
  out(['ok'=>true,'id'=>$id]);
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_admin_json();
  $id    = intval($_POST['id'] ?? 0);
  $first = $conn->real_escape_string($_POST['first_name'] ?? '');
  $last  = $conn->real_escape_string($_POST['last_name'] ?? '');
  $pin   = $_POST['pin'] ?? '';
  $is_admin = isset($_POST['is_admin']) ? (intval($_POST['is_admin']) ? 1 : 0) : 0;
  if ($first==='' || $last==='') { http_response_code(400); out(['error'=>'first/last required']); }
  if (!validate_required_pin($pin)) { http_response_code(400); out(['error'=>'PIN must be exactly 6 digits']); }
  $pin_e = $conn->real_escape_string($pin);
  $name  = trim($first.' '.$last);
  $sql = "UPDATE users SET first_name='$first', last_name='$last', pin='$pin_e', is_admin=$is_admin, name='$name' WHERE id=$id LIMIT 1";
  if (!$conn->query($sql)) { http_response_code(500); out(['error'=>$conn->error]); }
  if (function_exists('audit')) audit($conn,'user_update','user',$id,"name=$name;pin=$pin;is_admin=$is_admin");
  out(['ok'=>true]);
}

if ($action === 'disable' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_admin_json();
  $id = intval($_POST['id'] ?? 0);
  if (!$conn->query("UPDATE users SET disabled=1 WHERE id=$id LIMIT 1")) { http_response_code(500); out(['error'=>$conn->error]); }
  if (function_exists('audit')) audit($conn,'user_disable','user',$id,null);
  out(['ok'=>true]);
}

if ($action === 'enable' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_admin_json();
  $id = intval($_POST['id'] ?? 0);
  if (!$conn->query("UPDATE users SET disabled=0 WHERE id=$id LIMIT 1")) { http_response_code(500); out(['error'=>$conn->error]); }
  if (function_exists('audit')) audit($conn,'user_enable','user',$id,null);
  out(['ok'=>true]);
}

if ($action === 'softDelete' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_admin_json();
  $id = intval($_POST['id'] ?? 0);
  if (!$conn->query("UPDATE users SET deleted_at=NOW() WHERE id=$id LIMIT 1")) { http_response_code(500); out(['error'=>$conn->error]); }
  if (function_exists('audit')) audit($conn,'user_soft_delete','user',$id,null);
  out(['ok'=>true]);
}

if ($action === 'restore' && $_SERVER['REQUEST_METHOD']==='POST') {
  require_admin_json();
  $id = intval($_POST['id'] ?? 0);
  if (!$conn->query("UPDATE users SET deleted_at=NULL WHERE id=$id LIMIT 1")) { http_response_code(500); out(['error'=>$conn->error]); }
  if (function_exists('audit')) audit($conn,'user_restore','user',$id,null);
  out(['ok'=>true]);
}

http_response_code(400);
echo json_encode(['error'=>'Invalid action']);
?>