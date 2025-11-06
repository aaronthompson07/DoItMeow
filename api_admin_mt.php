<?php
// api_admin_mt.php — Admin-only CRUD for tasks, scoped by company_id
require_once 'db.php';
require_once 'auth_company.php';
require_once 'user_auth.php';
require_company();
header('Content-Type: application/json');

$u = current_user();
if (!$u || !$u['is_admin']) { http_response_code(403); echo json_encode(['error'=>'ADMIN_ONLY']); exit; }

$company_id = (int)($_SESSION['company_id'] ?? 0);
$action = $_GET['action'] ?? '';

function out($d){ echo json_encode($d); exit; }
function esc_like($s, $conn){ return $conn->real_escape_string($s); }

// LIST
if ($action === 'listTasks'){
  $include_deleted = !empty($_GET['include_deleted']) ? 1 : 0;
  $where = "t.company_id=$company_id";
  if (!$include_deleted) { $where .= " AND t.deleted_at IS NULL"; }
  $sql = "SELECT t.*, TRIM(CONCAT(COALESCE(u.first_name,''),' ',COALESCE(u.last_name,''))) AS assigned_user_name
          FROM tasks t
          LEFT JOIN users u ON u.id=t.assigned_user_id
          WHERE $where
          ORDER BY t.start_date DESC, t.id DESC";
  $res = $conn->query($sql);
  $rows=[]; while($r=$res->fetch_assoc()){ $rows[]=$r; }
  out($rows);
}

// GET
if ($action === 'getTask'){
  $id = (int)($_GET['id'] ?? 0);
  $sql = "SELECT * FROM tasks WHERE id=$id AND company_id=$company_id LIMIT 1";
  $res = $conn->query($sql);
  $row = $res ? $res->fetch_assoc() : null;
  out($row ?: null);
}

// CREATE
if ($action === 'createTask' && $_SERVER['REQUEST_METHOD']==='POST'){
  $title = $_POST['title'] ?? '';
  $description = $_POST['description'] ?? '';
  $timeframe = $_POST['timeframe'] ?? 'Morning';
  $type = $_POST['type'] ?? 'single';
  $start_date = $_POST['start_date'] ?? date('Y-m-d');
  $end_date = $_POST['end_date'] ?? '';
  $assigned_user_id = $_POST['assigned_user_id'] ?? '';

  if ($title===''){ http_response_code(400); out(['error'=>'TITLE_REQUIRED']); }

  $stmt = $conn->prepare("INSERT INTO tasks (company_id, title, description, timeframe, type, start_date, end_date, assigned_user_id)
                          VALUES (?, ?, ?, ?, ?, NULLIF(?, ''), NULLIF(?, ''), NULLIF(?, ''))");
  $stmt->bind_param('isssssss', $company_id, $title, $description, $timeframe, $type, $start_date, $end_date, $assigned_user_id);
  if (!$stmt || !$stmt->execute()){ http_response_code(500); out(['error'=>'DB']); }
  out(['ok'=>true, 'id'=>$stmt->insert_id]);
}

// UPDATE
if ($action === 'updateTask' && $_SERVER['REQUEST_METHOD']==='POST'){
  $id = (int)($_POST['id'] ?? 0);
  $title = $_POST['title'] ?? '';
  $description = $_POST['description'] ?? '';
  $timeframe = $_POST['timeframe'] ?? 'Morning';
  $type = $_POST['type'] ?? 'single';
  $start_date = $_POST['start_date'] ?? '';
  $end_date = $_POST['end_date'] ?? '';
  $assigned_user_id = $_POST['assigned_user_id'] ?? '';

  if ($id<=0 || $title===''){ http_response_code(400); out(['error'=>'INVALID']); }

  $stmt = $conn->prepare("UPDATE tasks
                          SET title=?, description=?, timeframe=?, type=?,
                              start_date=NULLIF(?, ''), end_date=NULLIF(?, ''),
                              assigned_user_id=NULLIF(?, '')
                          WHERE id=? AND company_id=?
                          LIMIT 1");
  $stmt->bind_param('sssssssii', $title, $description, $timeframe, $type, $start_date, $end_date, $assigned_user_id, $id, $company_id);
  if (!$stmt || !$stmt->execute()){ http_response_code(500); out(['error'=>'DB']); }
  out(['ok'=>true]);
}

// SOFT DELETE
if ($action === 'softDelete' && $_SERVER['REQUEST_METHOD']==='POST'){
  $id = (int)($_POST['id'] ?? 0);
  if ($id<=0){ http_response_code(400); out(['error'=>'INVALID']); }
  $conn->query("UPDATE tasks SET deleted_at = NOW() WHERE id=$id AND company_id=$company_id LIMIT 1");
  out(['ok'=>true]);
}

// RESTORE
if ($action === 'restore' && $_SERVER['REQUEST_METHOD']==='POST'){
  $id = (int)($_POST['id'] ?? 0);
  if ($id<=0){ http_response_code(400); out(['error'=>'INVALID']); }
  $conn->query("UPDATE tasks SET deleted_at = NULL WHERE id=$id AND company_id=$company_id LIMIT 1");
  out(['ok'=>true]);
}

http_response_code(400);
out(['error'=>'Invalid action']);
