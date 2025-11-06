<?php
// /arrowchat/diag/db_probe.php
// Purpose: verify DB config used by ArrowChat and test view availability.
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../includes/config.php';

echo "== DB Probe ==\n";
echo "DB: " . DB_NAME . "@" . DB_SERVER . " user=" . DB_USERNAME . "\n";

$mysqli = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
  echo "CONNECT ERROR: " . $mysqli->connect_error . "\n";
  exit;
}
echo "Connected OK.\n";

function q($mysqli, $sql) {
  echo "\nSQL> $sql\n";
  $res = $mysqli->query($sql);
  if (!$res) { echo "ERROR: " . $mysqli->error . "\n"; return; }
  $i=0;
  while ($row = $res->fetch_assoc()) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE), "\n";
    if (++$i >= 5) { echo "...\n"; break; }
  }
}

q($mysqli, "SHOW TABLES LIKE 'arrowchat_%'");
q($mysqli, "SHOW TABLES LIKE 'ac_users_for_chat'");
q($mysqli, "SELECT COUNT(*) AS cnt FROM ac_users_for_chat");
q($mysqli, "SELECT id, name, company_id FROM ac_users_for_chat ORDER BY id LIMIT 5");
q($mysqli, "SHOW COLUMNS FROM arrowchat_status");
q($mysqli, "SHOW COLUMNS FROM arrowchat_chatroom_rooms");

$mysqli->close();
echo "\nDone.\n";
?>