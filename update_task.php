<?php
require_once 'auth.php'; require_admin();
require_once 'db.php';
if (file_exists('audit.php')) { include_once 'audit.php'; }

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = $_POST['description'] ?? '';
$timeframe = $_POST['timeframe'] ?? 'Morning';
$type = $_POST['type'] ?? 'single';
$start_date = $_POST['start_date'] ?? null;
$end_date = $_POST['end_date'] ?? null;
$assigned_user_id = isset($_POST['assigned_user_id']) && $_POST['assigned_user_id'] !== '' ? (int)$_POST['assigned_user_id'] : null;

if ($start_date === '') $start_date = null;
if ($end_date === '') $end_date = null;

if ($id <= 0 || $title === '') { http_response_code(400); echo 'Invalid'; exit; }

// Build SQL dynamically so end_date can truly be NULL when blank
$sql = "UPDATE tasks SET title=?, description=?, timeframe=?, type=?, start_date=?, end_date=?, assigned_user_id=? WHERE id=? LIMIT 1";
$stmt = $conn->prepare($sql);
$end_date_param = $end_date; // can be null
$assigned_param = $assigned_user_id; // can be null
$stmt->bind_param(
  "ssssssii",
  $title,
  $description,
  $timeframe,
  $type,
  $start_date,
  $end_date_param,
  $assigned_param,
  $id
);

// If either nullable param is NULL, adjust using ->send_long_data or re-prepare with explicit NULLs
// Simpler approach: if NULLs exist, close and re-prepare with COALESCE logic
if ($end_date === null || $assigned_user_id === null) {
  $stmt->close();
  $sql = "UPDATE tasks
          SET title=?,
              description=?,
              timeframe=?,
              type=?,
              start_date=?,
              end_date=" . ($end_date === null ? "NULL" : "?") . ",
              assigned_user_id=" . ($assigned_user_id === null ? "NULL" : "?") . "
          WHERE id=? LIMIT 1";
  $stmt = $conn->prepare($sql);
  if ($end_date === null && $assigned_user_id === null) {
    $stmt->bind_param("sssssi",
      $title, $description, $timeframe, $type, $start_date, $id
    );
  } elseif ($end_date === null) {
    $stmt->bind_param("ssssssi",
      $title, $description, $timeframe, $type, $start_date, $assigned_user_id, $id
    );
  } elseif ($assigned_user_id === null) {
    $stmt->bind_param("ssssssi",
      $title, $description, $timeframe, $type, $start_date, $end_date, $id
    );
  }
}

if (!$stmt) { http_response_code(500); echo 'Prepare failed'; exit; }
if (!$stmt->execute()) { http_response_code(500); echo 'DB error'; exit; }
$stmt->close();

if (function_exists('audit')) {
  $details = sprintf(
    "tf=%s;type=%s;start=%s;end=%s;assigned=%s",
    $timeframe,
    $type,
    $start_date ?? 'NULL',
    $end_date ?? 'NULL',
    $assigned_user_id === null ? 'NULL' : (string)$assigned_user_id
  );
  audit($conn, 'task_update', 'task', $id, $details);
}
echo 'ok';
?>