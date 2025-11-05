<?php require_once 'auth.php'; require_admin(); include 'db.php'; include 'audit.php';
$name=$conn->real_escape_string($_POST['name']??''); if($name!==''){ $conn->query("INSERT INTO users(name) VALUES('$name')"); audit($conn,'user_add','user',$conn->insert_id,$name); } header('Location: admin.php'); ?>
