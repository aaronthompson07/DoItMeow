<?php
include 'db.php';
if (file_exists('audit.php')) { include 'audit.php'; }
$action = $_GET['action'] ?? '';
function out($d){ header('Content-Type: application/json'); echo json_encode($d); exit; }

if ($action==='getTasks'){
  $today = date('Y-m-d'); $weekday = date('w'); $day = date('j'); $rows=[];
  $res = $conn->query("SELECT * FROM tasks");
  while($r=$res->fetch_assoc()){
    $show=false;
    if($r['type']==='single' && $r['start_date']===$today) $show=true;
    if($r['type']==='daily' && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='weekly' && date('w', strtotime($r['start_date']))==$weekday && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='monthly' && date('j', strtotime($r['start_date']))==$day && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($show){
      // hide if completed today by anyone
      $tid = intval($r['id']);
      $done = $conn->query("SELECT 1 FROM task_completions WHERE task_id=$tid AND DATE(completed_at)=CURDATE() LIMIT 1");
      if(!$done->num_rows){
        $rows[]=$r;
      }
    }
  }
  out($rows);
}

if ($action==='getUsers'){
  $res=$conn->query("SELECT id,name FROM users ORDER BY name"); out($res->fetch_all(MYSQLI_ASSOC));
}

if ($action==='markComplete' && $_SERVER['REQUEST_METHOD']==='POST'){
  $task_id=intval($_POST['task_id']); $user_id=intval($_POST['user_id']);
  // Prevent duplicates within the same day
  $exists = $conn->query("SELECT 1 FROM task_completions WHERE task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  if($exists->num_rows===0){
    $conn->query("INSERT INTO task_completions (task_id,user_id) VALUES ($task_id,$user_id)");
    if (function_exists('audit')) { audit($conn, 'task_complete', 'task', $task_id, 'by_user_id=' . $user_id); }
  }
  echo 'success'; exit;
}

http_response_code(400); echo 'Invalid action';
?>