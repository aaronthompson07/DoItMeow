<?php
if (!function_exists('audit')) {
  function audit($conn, $action, $entity, $entity_id = null, $details = null) {
    if (!$conn) return;
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $actor = !empty($_SESSION['is_admin']) ? 'admin' : 'public';
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    $stmt = $conn->prepare("INSERT INTO audit_log (action, entity, entity_id, actor, ip, details) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssisss", $action, $entity, $entity_id, $actor, $ip, $details);
    @$stmt->execute();
    @$stmt->close();
  }
}
?>