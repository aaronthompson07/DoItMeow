<?php require_once 'auth.php'; require_admin(); include 'db.php'; include 'audit.php';
$title=$conn->real_escape_string($_POST['title']??''); $desc=$conn->real_escape_string($_POST['description']??''); $timeframe=$_POST['timeframe']??'Morning'; $type=$_POST['type']??'single'; $start=$_POST['start_date']??date('Y-m-d'); $end=$_POST['end_date']??NULL;
$conn->query("INSERT INTO tasks(title,description,timeframe,type,start_date,end_date) VALUES('$title','$desc','$timeframe','$type','$start',".($end?"'$end'":"NULL").")");
audit($conn,'task_add','task',$conn->insert_id,"type=$type;tf=$timeframe;start=$start;end=$end");
header('Location: admin.php'); ?>
