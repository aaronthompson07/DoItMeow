<?php
// create_company_chatroom.php — call after creating a new company
require_once __DIR__ . '/db.php';

function ensure_company_chatroom(mysqli $conn, int $company_id, string $company_name): int {
  $q = $conn->prepare("SELECT id FROM arrowchat_chatroom_rooms WHERE company_id=? LIMIT 1");
  $q->bind_param('i', $company_id);
  $q->execute();
  $res = $q->get_result();
  if ($row = $res->fetch_assoc()) return (int)$row['id'];
  $q->close();

  $name = 'Company Chat — ' . $company_name;
  $ins = $conn->prepare("INSERT INTO arrowchat_chatroom_rooms (company_id, name, created) VALUES (?, ?, UNIX_TIMESTAMP())");
  $ins->bind_param('is', $company_id, $name);
  $ins->execute();
  $rid = $ins->insert_id;
  $ins->close();
  return $rid;
}

// Example usage:
/*
$rid = ensure_company_chatroom($conn, 1, 'Default Company');
echo "Room: $rid";
*/
