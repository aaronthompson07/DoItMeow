<?php
include 'db.php';
if (file_exists('audit.php')) { include 'audit.php'; }
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

function out($d){ echo json_encode($d); exit; }

if ($action==='getTasks'){ // today's visible (not completed today)
  $today = date('Y-m-d'); $weekday = date('w'); $day = date('j'); $rows=[];
  $res = $conn->query("SELECT t.*, u.name AS assigned_user_name FROM tasks t LEFT JOIN users u ON u.id=t.assigned_user_id WHERE t.deleted_at IS NULL");
  while($r=$res->fetch_assoc()){
    $show=false;
    if($r['type']==='single' && $r['start_date']===$today) $show=true;
    if($r['type']==='daily' && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='weekly' && date('w', strtotime($r['start_date']))==$weekday && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='monthly' && date('j', strtotime($r['start_date']))==$day && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($show){
      $tid = intval($r['id']);
      $done = $conn->query("SELECT 1 FROM task_completions WHERE task_id=$tid AND DATE(completed_at)=CURDATE() LIMIT 1");
      if(!$done->num_rows){ $rows[]=$r; }
    }
  }
  out($rows);
}

if ($action==='statsToday'){ // KPI counts
  $today = date('Y-m-d'); $weekday = date('w'); $day = date('j');
  $open = ['Morning'=>0,'Mid-Day'=>0,'Afternoon'=>0,'Anytime'=>0];
  $total_open = 0;

  // pull all tasks that are "scheduled" today regardless completed; then subtract completed
  $res = $conn->query("SELECT id,timeframe,type,start_date,end_date FROM tasks WHERE deleted_at IS NULL");
  $ids_today = [];
  while($r=$res->fetch_assoc()){
    $show=false;
    if($r['type']==='single' && $r['start_date']===$today) $show=true;
    if($r['type']==='daily' && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='weekly' && date('w', strtotime($r['start_date']))==$weekday && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='monthly' && date('j', strtotime($r['start_date']))==$day && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($show){
      $ids_today[$r['id']] = $r['timeframe'];
    }
  }
  // completions today
  $done_ids = [];
  $c = $conn->query("SELECT task_id FROM task_completions WHERE DATE(completed_at)=CURDATE()");
  while($row=$c->fetch_assoc()){ $done_ids[intval($row['task_id'])]=true; }
  foreach($ids_today as $tid=>$tf){
    if (!isset($done_ids[$tid])) { $total_open++; if(isset($open[$tf])) $open[$tf]++; }
  }
  $completed_count = count($done_ids);

  out([
    'remaining_total'=>$total_open,
    'completed_today'=>$completed_count,
    'remaining_by_timeframe'=>$open
  ]);
}

if ($action==='getUsers'){
  $sql = "SELECT id, TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) AS name
          FROM users
          WHERE (deleted_at IS NULL) AND (disabled=0)
          ORDER BY last_name, first_name, name";
  $res=$conn->query($sql);
  out($res->fetch_all(MYSQLI_ASSOC));
}

if ($action==='assignTask' && $_SERVER['REQUEST_METHOD']==='POST'){
  if (session_status()===PHP_SESSION_NONE) session_start();
  if (empty($_SESSION['is_admin'])) { http_response_code(403); out(['error'=>'admin required']); }
  $task_id = intval($_POST['task_id']);
  $user_id = isset($_POST['user_id']) && $_POST['user_id']!=='' ? intval($_POST['user_id']) : 'NULL';
  $conn->query("UPDATE tasks SET assigned_user_id=$user_id WHERE id=$task_id");
  if (function_exists('audit')) { audit($conn, 'task_assign', 'task', $task_id, 'assigned_user_id=' . ($user_id==='NULL'?'NULL':$user_id)); }
  out(['ok'=>true]);
}

if ($action==='markComplete' && $_SERVER['REQUEST_METHOD']==='POST'){
  $task_id=intval($_POST['task_id']); $user_id=intval($_POST['user_id']);
  $ok_user = $conn->query("SELECT 1 FROM users WHERE id=$user_id AND disabled=0 AND deleted_at IS NULL")->num_rows;
  if(!$ok_user){ http_response_code(400); echo 'invalid user'; exit; }
  $exists = $conn->query("SELECT 1 FROM task_completions WHERE task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  if($exists->num_rows===0){
    $conn->query("INSERT INTO task_completions (task_id,user_id) VALUES ($task_id,$user_id)");
    if (function_exists('audit')) { audit($conn, 'task_complete', 'task', $task_id, 'by_user_id=' . $user_id); }
  }
  echo json_encode(['ok'=>true]); exit;
}

if ($action==='uncompleteTask' && $_SERVER['REQUEST_METHOD']==='POST'){
  $task_id = intval($_POST['task_id'] ?? 0);
  if ($task_id<=0){ http_response_code(400); echo 'invalid'; exit; }
  $conn->query("DELETE FROM task_completions WHERE task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  if (function_exists('audit')) { audit($conn, 'task_uncomplete', 'task', $task_id, null); }
  echo json_encode(['ok'=>true]); exit;
}

if ($action==='recentCompletions'){
  $sql = "SELECT tc.id, tc.task_id, tc.completed_at,
                 t.title, t.timeframe,
                 u.name AS completed_by
          FROM task_completions tc
          LEFT JOIN tasks t ON t.id = tc.task_id
          LEFT JOIN users u ON u.id = tc.user_id
          WHERE DATE(tc.completed_at)=CURDATE()
          ORDER BY tc.completed_at DESC
          LIMIT 50";
  $res = $conn->query($sql);
  $rows = [];
  while($r=$res->fetch_assoc()){ $rows[]=$r; }
  out($rows);
}

if ($action==='listTasks'){
  $include_deleted = isset($_GET['include_deleted']) ? (int)$_GET['include_deleted'] : 0;
  $where = $include_deleted ? "1=1" : "t.deleted_at IS NULL";
  $sql = "SELECT t.*, u.name AS assigned_user_name
          FROM tasks t
          LEFT JOIN users u ON u.id = t.assigned_user_id
          WHERE $where
          ORDER BY t.start_date DESC, t.id DESC";
  $res = $conn->query($sql);
  $rows = [];
  while($r=$res->fetch_assoc()){
    $rows[] = $r;
  }
  out($rows);
}

if ($action==='getTask'){
  $id = intval($_GET['id'] ?? 0);
  $res = $conn->query("SELECT * FROM tasks WHERE id=$id LIMIT 1");
  $row = $res ? $res->fetch_assoc() : null;
  out($row ?: []);
}

http_response_code(400);
echo json_encode(['error'=>'Invalid action']);
?>