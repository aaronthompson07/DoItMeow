<?php
// === Add these handlers to your existing api.php (keep your other endpoints as-is) ===
// Place below your existing handlers, before the final 400 response.
if (isset($_GET['action']) && $_GET['action']==='uncompleteTask' && $_SERVER['REQUEST_METHOD']==='POST'){
  include_once 'db.php';
  if (file_exists('audit.php')) { include_once 'audit.php'; }
  $task_id = intval($_POST['task_id'] ?? 0);
  if ($task_id<=0){ http_response_code(400); echo 'invalid'; exit; }
  $conn->query("DELETE FROM task_completions WHERE task_id=$task_id AND DATE(completed_at)=CURDATE() LIMIT 1");
  if (function_exists('audit')) { audit($conn, 'task_uncomplete', 'task', $task_id, null); }
  echo json_encode(['ok'=>true]); exit;
}

if (isset($_GET['action']) && $_GET['action']==='recentCompletions'){
  include_once 'db.php';
  $sql = "SELECT tc.id, tc.task_id, tc.completed_at,
                 t.title, t.timeframe,
                 COALESCE(CONCAT(TRIM(COALESCE(u.first_name,'')),' ',TRIM(COALESCE(u.last_name,''))), u.name) AS completed_by
          FROM task_completions tc
          LEFT JOIN tasks t ON t.id = tc.task_id
          LEFT JOIN users u ON u.id = tc.user_id
          WHERE DATE(tc.completed_at)=CURDATE()
          ORDER BY tc.completed_at DESC
          LIMIT 50";
  $res = $conn->query($sql);
  $rows = [];
  while($r=$res->fetch_assoc()){ $rows[]=$r; }
  header('Content-Type: application/json');
  echo json_encode($rows); exit;
}
?>