<?php
// api_kpis.php — self-contained endpoints so you don't have to modify your existing api.php
// Provides: dashboardStats, recentCompletions, uncompleteTask
// Assumes tables:
//  - tasks(id, timeframe, type, start_date, end_date, deleted_at)
//  - task_completions(id, task_id, user_id, completed_at)
//  - inventory_sessions(id, closed_at)            [optional — zero if table missing]
//  - training_assignments(id, completed_at)       [optional — zero if table missing]

if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once 'db.php';

$action = $_GET['action'] ?? '';

header('Content-Type: application/json');

function table_exists($conn, $name){
  $name_esc = $conn->real_escape_string($name);
  $res = $conn->query("SHOW TABLES LIKE '$name_esc'");
  return $res && $res->num_rows > 0;
}

if ($action === 'dashboardStats') {
  $today = date('Y-m-d'); $weekday = date('w'); $day = date('j');

  // Totals by timeframe for tasks that are scheduled today
  $tb = ['Morning'=>0,'Mid-Day'=>0,'Afternoon'=>0,'Anytime'=>0];
  $ids_today = [];
  $sql = "SELECT id,timeframe,type,start_date,end_date FROM tasks WHERE deleted_at IS NULL";
  if ($res = $conn->query($sql)) {
    while ($r = $res->fetch_assoc()) {
      $show = false;
      $sd = $r['start_date'];
      $ed = $r['end_date'];
      $type = $r['type'];

      if ($type === 'single' && $sd === $today) $show = true;
      if ($type === 'daily' && $sd <= $today && (empty($ed) || $ed >= $today)) $show = true;
      if ($type === 'weekly' && date('w', strtotime($sd)) == $weekday && $sd <= $today && (empty($ed) || $ed >= $today)) $show = true;
      if ($type === 'monthly' && date('j', strtotime($sd)) == $day && $sd <= $today && (empty($ed) || $ed >= $today)) $show = true;

      if ($show) {
        $ids_today[] = (int)$r['id'];
        if (isset($tb[$r['timeframe']])) $tb[$r['timeframe']]++;
      }
    }
  }

  // Completed today (distinct tasks)
  $completed_today = 0;
  if (!empty($ids_today)) {
    $idlist = implode(',', array_map('intval', $ids_today));
    $res2 = $conn->query("SELECT COUNT(DISTINCT task_id) AS c FROM task_completions WHERE DATE(completed_at)=CURDATE() AND task_id IN ($idlist)");
    if ($res2 && ($row = $res2->fetch_assoc())) $completed_today = (int)$row['c'];
  }

  // Inventory sessions open
  $inv_open = 0;
  if (table_exists($conn, 'inventory_sessions')) {
    $r3 = $conn->query("SELECT COUNT(*) AS c FROM inventory_sessions WHERE closed_at IS NULL");
    if ($r3 && ($row = $r3->fetch_assoc())) $inv_open = (int)$row['c'];
  }

  // Trainings pending
  $train_pending = 0;
  if (table_exists($conn, 'training_assignments')) {
    $r4 = $conn->query("SELECT COUNT(*) AS c FROM training_assignments WHERE completed_at IS NULL");
    if ($r4 && ($row = $r4->fetch_assoc())) $train_pending = (int)$row['c'];
  }

  echo json_encode([
    'total_by_timeframe' => $tb,
    'completed_today' => $completed_today,
    'open_inventory_sessions' => $inv_open,
    'trainings_pending' => $train_pending
  ]);
  exit;
}

if ($action === 'recentCompletions') {
  $sql = "SELECT tc.id, tc.task_id, tc.completed_at,
                 t.title, t.timeframe,
                 TRIM(CONCAT(COALESCE(u.first_name,''),' ',COALESCE(u.last_name,''))) AS completed_by
          FROM task_completions tc
          LEFT JOIN tasks t ON t.id = tc.task_id
          LEFT JOIN users u ON u.id = tc.user_id
          WHERE DATE(tc.completed_at)=CURDATE()
          ORDER BY tc.completed_at DESC
          LIMIT 50";
  $rows = [];
  if ($res = $conn->query($sql)) {
    while ($r = $res->fetch_assoc()) $rows[] = $r;
  }
  echo json_encode($rows); exit;
}

if ($action === 'uncompleteTask' && $_SERVER['REQUEST_METHOD'] === 'POST') {
  if (file_exists('audit.php')) { include_once 'audit.php'; }
  $task_id = (int)($_POST['task_id'] ?? 0);
  if ($task_id <= 0) { http_response_code(400); echo json_encode(['error'=>'invalid']); exit; }
  $conn->query("DELETE FROM task_completions WHERE task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  if (function_exists('audit')) { audit($conn, 'task_uncomplete', 'task', $task_id, null); }
  echo json_encode(['ok'=>true]); exit;
}

http_response_code(400);
echo json_encode(['error'=>'Invalid action']);
