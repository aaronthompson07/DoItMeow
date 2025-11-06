// --- Company chatroom ensure (schema-aware) ----------------------
if (($_GET['action'] ?? '') === 'room_id') {
  if (!$company_id) { echo json_encode(['room_id'=>null]); exit; }

  // helper to test columns
  $has = function(string $table, string $col) use ($conn): bool {
    $t = $conn->real_escape_string($table);
    $c = $conn->real_escape_string($col);
    $sql = "SELECT 1 FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = '{$t}'
              AND COLUMN_NAME = '{$c}'
            LIMIT 1";
    $res = $conn->query($sql);
    $ok = $res && (bool)$res->fetch_row();
    if ($res) $res->close();
    return $ok;
  };

  // Return existing room if present
  $room = $conn->prepare("SELECT id FROM arrowchat_chatroom_rooms WHERE company_id=? LIMIT 1");
  $room->bind_param('i', $company_id);
  $room->execute();
  $res = $room->get_result();
  if ($row = $res->fetch_assoc()) {
    echo json_encode(['room_id'=>(int)$row['id']]); exit;
  }
  $room->close();

  // Build INSERT with only the columns your schema actually has
  $cols  = ['company_id','name'];
  $vals  = ['?','?'];
  $types = 'is';
  $bind  = [$company_id, 'Company Chat — ' . ($_SESSION['company_name'] ?? 'Company')];

  // REQUIRED in your build
  if ($has('arrowchat_chatroom_rooms','author_id')) {
    $cols[]='author_id'; $vals[]='?'; $types.='s'; $bind[] = $userid ? (string)$userid : '0';
  }

  // NEW: some builds require `type` (0 = public, 1 = password, 2 = staff-only, etc.)
  if ($has('arrowchat_chatroom_rooms','type')) {
    $cols[]='type'; $vals[]='?'; $types.='i'; $bind[] = 0; // public room
  }

  // Optional columns
  if ($has('arrowchat_chatroom_rooms','description'))      { $cols[]='description';      $vals[]='?'; $types.='s'; $bind[]=''; }
  if ($has('arrowchat_chatroom_rooms','welcome_message'))  { $cols[]='welcome_message';  $vals[]='?'; $types.='s'; $bind[]=''; }
  if ($has('arrowchat_chatroom_rooms','created'))          { $cols[]='created';          $vals[]='?'; $types.='i'; $bind[]=time(); }
  if ($has('arrowchat_chatroom_rooms','last_activity'))    { $cols[]='last_activity';    $vals[]='?'; $types.='i'; $bind[]=time(); }
  if ($has('arrowchat_chatroom_rooms','password'))         { $cols[]='password';         $vals[]='?'; $types.='s'; $bind[]=''; }
  if ($has('arrowchat_chatroom_rooms','max_users'))        { $cols[]='max_users';        $vals[]='?'; $types.='i'; $bind[]=0; } // 0 = unlimited

  $sql = "INSERT INTO arrowchat_chatroom_rooms (".implode(',', $cols).") VALUES (".implode(',', $vals).")";
  $ins = $conn->prepare($sql);
  if (!$ins) {
    http_response_code(500);
    echo json_encode(['room_id'=>null, 'error'=>'prepare_failed']);
    exit;
  }
  $ins->bind_param($types, ...$bind);
  $ins->execute();
  $rid = $ins->insert_id;
  $ins->close();

  echo json_encode(['room_id'=>(int)$rid]); exit;
}
