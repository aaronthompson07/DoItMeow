<?php
require_once 'db.php';
require_once 'auth_company.php';
require_once 'user_auth.php';
require_company();

header('Content-Type: application/json');
$company_id = current_company_id();
$action = $_GET['action'] ?? '';

function out($d){ echo json_encode($d); exit; }
function table_exists($conn, $name){
  $name_esc = $conn->real_escape_string($name);
  $res = $conn->query("SHOW TABLES LIKE '$name_esc'");
  return $res && $res->num_rows > 0;
}

if ($action==='whoami'){
  $u = current_user();
  out(['company_id'=>$company_id, 'user'=>$u]);
}

if ($action==='loginPin' && $_SERVER['REQUEST_METHOD']==='POST'){
  $pin = $_POST['pin'] ?? '';
  if (!preg_match('/^[0-9]{6}$/', $pin)) { http_response_code(400); out(['error'=>'INVALID_PIN']); }
  $stmt = $conn->prepare("SELECT id, first_name, last_name, is_admin FROM users WHERE company_id=? AND pin=? AND disabled=0 AND deleted_at IS NULL LIMIT 1");
  $stmt->bind_param('is', $company_id, $pin);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($row = $res->fetch_assoc()){
    set_user($row['id'], $row['first_name'], $row['last_name'], (int)$row['is_admin']);
    out(['ok'=>true, 'user'=>current_user()]);
  } else {
    http_response_code(403); out(['error'=>'PIN_NOT_FOUND']);
  }
}

if ($action==='logoutUser' && $_SERVER['REQUEST_METHOD']==='POST'){
  clear_user();
  out(['ok'=>true]);
}

// ===== DASHBOARD / TASKS =====

if ($action==='getTasks'){
  $today = date('Y-m-d'); $weekday = date('w'); $day = date('j'); $rows=[];
  $res = $conn->query("SELECT t.*, TRIM(CONCAT(COALESCE(u.first_name,''),' ',COALESCE(u.last_name,''))) AS assigned_user_name
                       FROM tasks t
                       LEFT JOIN users u ON u.id=t.assigned_user_id
                       WHERE t.company_id={$company_id} AND t.deleted_at IS NULL");
  while($r=$res->fetch_assoc()){
    $show=false;
    if($r['type']==='single' && $r['start_date']===$today) $show=true;
    if($r['type']==='daily' && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='weekly' && date('w', strtotime($r['start_date']))==$weekday && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='monthly' && date('j', strtotime($r['start_date']))==$day && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($show){
      $tid = (int)$r['id'];
      $done = $conn->query("SELECT 1 FROM task_completions WHERE company_id={$company_id} AND task_id=$tid AND DATE(completed_at)=CURDATE() LIMIT 1");
      if(!$done->num_rows){ $rows[]=$r; }
    }
  }
  out($rows);
}

if ($action==='markComplete' && $_SERVER['REQUEST_METHOD']==='POST'){
  $u = current_user();
  if (!$u){ http_response_code(401); out(['error'=>'USER_NOT_AUTH']); }
  $task_id = (int)($_POST['task_id'] ?? 0);
  if ($task_id<=0) { http_response_code(400); out(['error'=>'BAD_TASK']); }
  // prevent dup same day
  $exists = $conn->query("SELECT 1 FROM task_completions WHERE company_id={$company_id} AND task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  if (!$exists->num_rows){
    $stmt = $conn->prepare("INSERT INTO task_completions (company_id, task_id, user_id) VALUES (?,?,?)");
    $stmt->bind_param('iii', $company_id, $task_id, $u['id']);
    $stmt->execute(); $stmt->close();
  }
  out(['ok'=>true]);
}

if ($action==='dashboardStats'){
  $today = date('Y-m-d'); $weekday = date('w'); $day = date('j');
  $tb = ['Morning'=>0,'Mid-Day'=>0,'Afternoon'=>0,'Anytime'=>0];
  $ids_today = [];
  $res = $conn->query("SELECT id,timeframe,type,start_date,end_date FROM tasks WHERE company_id={$company_id} AND deleted_at IS NULL");
  while($r=$res->fetch_assoc()){
    $show=false;
    if($r['type']==='single' && $r['start_date']===$today) $show=true;
    if($r['type']==='daily' && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='weekly' && date('w', strtotime($r['start_date']))==$weekday && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='monthly' && date('j', strtotime($r['start_date']))==$day && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($show){
      $ids_today[] = (int)$r['id'];
      if(isset($tb[$r['timeframe']])) $tb[$r['timeframe']]++;
    }
  }
  $completed_today = 0;
  if (!empty($ids_today)){
    $idlist = implode(',', $ids_today);
    $r2 = $conn->query("SELECT COUNT(DISTINCT task_id) AS c FROM task_completions WHERE company_id={$company_id} AND DATE(completed_at)=CURDATE() AND task_id IN ($idlist)");
    $completed_today = ($r2 && ($row=$r2->fetch_assoc())) ? (int)$row['c'] : 0;
  }
  $inv_open = 0;
  if (table_exists($conn, 'inventory_sessions')) {
    $r3 = $conn->query("SELECT COUNT(*) AS c FROM inventory_sessions WHERE company_id={$company_id} AND closed_at IS NULL");
    $inv_open = ($r3 && ($row=$r3->fetch_assoc())) ? (int)$row['c'] : 0;
  }
  $train_pending = 0;
  if (table_exists($conn, 'training_assignments')) {
    $r4 = $conn->query("SELECT COUNT(*) AS c FROM training_assignments WHERE company_id={$company_id} AND completed_at IS NULL");
    $train_pending = ($r4 && ($row=$r4->fetch_assoc())) ? (int)$row['c'] : 0;
  }
  out([
    'total_by_timeframe'=>$tb,
    'completed_today'=>$completed_today,
    'open_inventory_sessions'=>$inv_open,
    'trainings_pending'=>$train_pending
  ]);
}

if ($action==='recentCompletions'){
  $sql = "SELECT tc.id, tc.task_id, tc.completed_at,
                 t.title, t.timeframe,
                 TRIM(CONCAT(COALESCE(u.first_name,''),' ',COALESCE(u.last_name,''))) AS completed_by
          FROM task_completions tc
          LEFT JOIN tasks t ON t.id = tc.task_id
          LEFT JOIN users u ON u.id = tc.user_id
          WHERE tc.company_id={$company_id} AND DATE(tc.completed_at)=CURDATE()
          ORDER BY tc.completed_at DESC
          LIMIT 50";
  $res = $conn->query($sql);
  $rows = [];
  while($r=$res->fetch_assoc()){ $rows[]=$r; }
  out($rows);
}

if ($action==='uncompleteTask' && $_SERVER['REQUEST_METHOD']==='POST'){
  $task_id = (int)($_POST['task_id'] ?? 0);
  if ($task_id<=0){ http_response_code(400); out(['error'=>'BAD_TASK']); }
  $conn->query("DELETE FROM task_completions WHERE company_id={$company_id} AND task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  out(['ok'=>true]);
}

http_response_code(400);
out(['error'=>'Invalid action']);
?>