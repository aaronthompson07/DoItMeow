<?php
include 'db.php';
header('Content-Type: application/json');
$action = $_GET['action'] ?? '';
function out($d){ echo json_encode($d); exit; }

if ($action === 'kpis') {
  $today = date('Y-m-d');
  $weekday = date('w');
  $day = date('j');

  // Tasks visible today
  $res = $conn->query("SELECT timeframe, type, start_date, end_date, id FROM tasks");
  $counts = ['Morning'=>0, 'Mid-Day'=>0, 'Afternoon'=>0];
  while($r = $res->fetch_assoc()){
    $show=false;
    if($r['type']==='single' && $r['start_date']===$today) $show=true;
    if($r['type']==='daily' && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='weekly' && date('w', strtotime($r['start_date']))==$weekday && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($r['type']==='monthly' && date('j', strtotime($r['start_date']))==$day && $r['start_date']<= $today && (empty($r['end_date'])||$r['end_date']>= $today)) $show=true;
    if($show){
      // Exclude tasks completed today
      $tid = intval($r['id']);
      $done = $conn->query("SELECT 1 FROM task_completions WHERE task_id=$tid AND DATE(completed_at)=CURDATE() LIMIT 1");
      if(!$done->num_rows && isset($counts[$r['timeframe']])) $counts[$r['timeframe']]++;
    }
  }

  // Completions today (+ recent list)
  $recent = [];
  $q = "SELECT tc.completed_at, u.name AS user_name, t.title, t.timeframe
        FROM task_completions tc
        JOIN users u ON u.id=tc.user_id
        JOIN tasks t ON t.id=tc.task_id
        WHERE DATE(tc.completed_at) = CURDATE()
        ORDER BY tc.completed_at DESC
        LIMIT 10";
  $r = $conn->query($q);
  while($row = $r->fetch_assoc()) $recent[] = $row;
  $total_completed_today = $conn->query("SELECT COUNT(*) AS c FROM task_completions WHERE DATE(completed_at)=CURDATE()")->fetch_assoc()['c'] ?? 0;

  // Inventory open session
  $inv = $conn->query("SELECT id, started_at FROM inventory_counts WHERE status='open' ORDER BY started_at DESC LIMIT 1");
  $inv_row = $inv->fetch_assoc();
  $inventory = $inv_row ? ['open'=>true, 'id'=>intval($inv_row['id']), 'started_at'=>$inv_row['started_at']] : ['open'=>false];

  // Training pending (templates + custom)
  $tmpl_pending = $conn->query("SELECT COUNT(*) AS c FROM training_assignments WHERE completed_at IS NULL")->fetch_assoc()['c'] ?? 0;
  $cust_pending = $conn->query("SELECT COUNT(*) AS c FROM custom_training_tasks WHERE completed_at IS NULL")->fetch_assoc()['c'] ?? 0;

  out([
    'tasks_today' => [
      'total' => $counts['Morning'] + $counts['Mid-Day'] + $counts['Afternoon'],
      'morning' => $counts['Morning'],
      'midday' => $counts['Mid-Day'],
      'afternoon' => $counts['Afternoon']
    ],
    'completed_today' => [
      'total' => intval($total_completed_today),
      'recent' => $recent
    ],
    'inventory' => $inventory,
    'training' => [
      'pending_total' => intval($tmpl_pending) + intval($cust_pending)
    ]
  ]);
}

http_response_code(400);
echo json_encode(['error' => 'Invalid action']);
?>