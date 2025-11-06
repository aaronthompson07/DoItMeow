<?php require_once 'auth.php'; require_admin(); include 'db.php'; if (file_exists('audit.php')) include 'audit.php';
$title=$conn->real_escape_string($_POST['title']??''); 
$desc=$conn->real_escape_string($_POST['description']??'');
$timeframe=$_POST['timeframe']??'Morning'; 
$type=$_POST['type']??'single'; 
$start=$_POST['start_date']??date('Y-m-d'); 
$end=$_POST['end_date']??NULL;
$assigned = isset($_POST['assigned_user_id']) && $_POST['assigned_user_id']!=='' ? intval($_POST['assigned_user_id']) : 'NULL';
$conn->query("INSERT INTO tasks(title,description,assigned_user_id,timeframe,type,start_date,end_date) VALUES('$title','$desc',$assigned,'$timeframe','$type','$start',".($end?"'$end'":"NULL").")");
if (function_exists('audit')) audit($conn,'task_add','task',$conn->insert_id,"type=$type;tf=$timeframe;start=$start;end=$end;assigned=$assigned");
header('Location: admin.php'); ?>
