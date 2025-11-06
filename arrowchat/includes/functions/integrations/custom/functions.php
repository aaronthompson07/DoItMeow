<?php
/**
 * ArrowChat Custom Integration (company-scoped, self-contained)
 * Works from either path:
 *  - /arrowchat/includes/integrations/custom/functions.php
 *  - /arrowchat/includes/functions/integrations/custom/functions.php
 *
 * Uses ArrowChat's own DB config to connect (no external app includes).
 * Relies on DoItMeow sessions:
 *   $_SESSION['company_id'] (set by company login)
 *   $_SESSION['user']       (id/first_name/last_name/is_admin) set by PIN login
 */

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Resolve ArrowChat config path (supports common layouts)
$cfg_try = [
  // if we're under /includes/integrations/custom/ → up 3 gets /includes
  dirname(__FILE__, 3) . '/config.php',
  // if we're under /includes/functions/integrations/custom/ → up 4 gets /includes
  dirname(__FILE__, 4) . '/config.php',
  // fallback to root
  dirname(__FILE__, 5) . '/config.php',
  dirname(__FILE__, 2) . '/config.php',
];

$cfg_loaded = false;
foreach ($cfg_try as $cfg) {
  if (is_file($cfg)) { require_once $cfg; $cfg_loaded = true; break; }
}
if (!$cfg_loaded) {
  // Fail soft to avoid blank external.php
  if (!function_exists('get_user_id')) { function get_user_id(){ return 0; } }
  if (!function_exists('get_username')) { function get_username($userid){ return ''; } }
  if (!function_exists('get_buddy_list')) { function get_buddy_list($userid,$time,$online){ return []; } }
  if (!function_exists('get_online_list')) { function get_online_list($userid,$time){ return []; } }
  if (!function_exists('chatroom_access_list')) { function chatroom_access_list($userid){ return []; } }
  return;
}

$ac_db = @new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($ac_db && $ac_db->connect_errno) {
  $ac_db = null; // fail gracefully
}

function ac_company_id() { return isset($_SESSION['company_id']) ? (int)$_SESSION['company_id'] : 0; }
function ac_user_id()    { return !empty($_SESSION['user']['id']) ? (int)$_SESSION['user']['id'] : 0; }

/** REQUIRED */
function get_user_id() { return ac_user_id(); }

/** REQUIRED */
function get_username($userid) {
  global $ac_db;
  if (!$ac_db) return '';
  $userid = (int)$userid;
  $sql = "SELECT name FROM ac_users_for_chat WHERE id={$userid} LIMIT 1";
  if ($res = $ac_db->query($sql)) {
    if ($row = $res->fetch_assoc()) return $row['name'];
  }
  return '';
}

/** Everyone in company is a "friend" (minus self) */
function get_buddy_list($userid, $time, $online) {
  global $ac_db;
  if (!$ac_db) return [];
  $userid = (int)$userid;
  $cid = ac_company_id();
  if ($cid <= 0) return [];

  $rows = [];
  $sql = "
    SELECT a.id, a.name
    FROM ac_users_for_chat a
    WHERE a.company_id = {$cid}
      AND a.disabled = 0
      AND a.deleted_at IS NULL
      AND a.id <> {$userid}
    ORDER BY a.name ASC
    LIMIT 500
  ";
  if ($res = $ac_db->query($sql)) {
    while ($r = $res->fetch_assoc()) { $rows[] = ['id'=>(int)$r['id'], 'name'=>$r['name']]; }
  }
  return $rows;
}

/** Online list (when NO_FREIND_SYSTEM=1), scoped to company */
function get_online_list($userid, $time) {
  global $ac_db;
  if (!$ac_db) return [];
  $userid = (int)$userid;
  $cid = ac_company_id();
  if ($cid <= 0) return [];

  $rows = [];
  $sql = "
    SELECT u.id, u.name
    FROM ac_users_for_chat u
    JOIN arrowchat_status s ON CAST(s.userid AS UNSIGNED) = u.id
    WHERE u.company_id = {$cid}
      AND u.disabled = 0
      AND u.deleted_at IS NULL
      AND u.id <> {$userid}
      AND (s.hide_me = 0 OR s.hide_me IS NULL)
      AND (s.is_banned = 0 OR s.is_banned IS NULL)
    ORDER BY u.name ASC
    LIMIT 500
  ";
  if ($res = $ac_db->query($sql)) {
    while ($r = $res->fetch_assoc()) { $rows[] = ['id'=>(int)$r['id'], 'name'=>$r['name']]; }
  }
  return $rows;
}

/** Restrict rooms to the current company */
function chatroom_access_list($userid) {
  global $ac_db;
  if (!$ac_db) return [];
  $cid = ac_company_id();
  $ids = [];
  if ($cid <= 0) return $ids;

  $res = $ac_db->query("SELECT id FROM arrowchat_chatroom_rooms WHERE company_id={$cid} ORDER BY id ASC");
  if ($res) { while ($r = $res->fetch_assoc()) { $ids[] = (int)$r['id']; } }
  return $ids;
}
