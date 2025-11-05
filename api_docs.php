<?php
include 'db.php'; include 'audit.php'; header('Content-Type: application/json'); $a=$_GET['action']??'';
function out($d){ echo json_encode($d); exit; }
function is_admin(){ if (session_status()===PHP_SESSION_NONE) session_start(); return !empty($_SESSION['is_admin']); }

if($a==='listCategories'){ $r=$conn->query("SELECT id,name FROM doc_categories ORDER BY name"); out($r->fetch_all(MYSQLI_ASSOC)); }
if($a==='listDocsByCategory'){ $r=$conn->query("SELECT d.id,d.title,d.category_id,c.name AS category_name FROM docs d JOIN doc_categories c ON c.id=d.category_id ORDER BY c.name,d.title"); out($r->fetch_all(MYSQLI_ASSOC)); }
if($a==='getDoc'){ $id=intval($_GET['id']); $r=$conn->query("SELECT * FROM docs WHERE id=$id"); out($r->fetch_assoc()); }

if($a==='saveDoc' && is_admin()){
  $id=intval($_POST['id']); $content=$conn->real_escape_string($_POST['content']);
  $conn->query("UPDATE docs SET content='$content' WHERE id=$id");
  audit($conn, 'doc_save', 'doc', $id, 'length=' . strlen($_POST['content'] ?? ''));
  out(['ok'=>true]);
}

if($a==='addCategory' && is_admin()){ $name=$conn->real_escape_string($_POST['name']); $conn->query("INSERT INTO doc_categories(name) VALUES('$name')"); audit($conn,'doc_category_add','doc_category',$conn->insert_id,$name); out(['ok'=>true]); }
if($a==='renameCategory' && is_admin()){ $id=intval($_POST['id']); $name=$conn->real_escape_string($_POST['name']); $conn->query("UPDATE doc_categories SET name='$name' WHERE id=$id"); audit($conn,'doc_category_rename','doc_category',$id,$name); out(['ok'=>true]); }
if($a==='deleteCategory' && is_admin()){ $id=intval($_POST['id']); $r=$conn->query("SELECT COUNT(*) c FROM docs WHERE category_id=$id"); if($r->fetch_assoc()['c']>0){ out(['ok'=>False,'error'=>'Category not empty. Move or delete docs first.']); } $conn->query("DELETE FROM doc_categories WHERE id=$id"); audit($conn,'doc_category_delete','doc_category',$id,null); out(['ok'=>true]); }
if($a==='addDoc' && is_admin()){ $title=$conn->real_escape_string($_POST['title']); $cat=intval($_POST['category_id']); $conn->query("INSERT INTO docs(title,category_id,content) VALUES('$title',$cat,'')"); audit($conn,'doc_add','doc',$conn->insert_id,$title); out(['ok'=>true,'id'=>$conn->insert_id]); }
if($a==='moveDoc' && is_admin()){ $id=intval($_POST['id']); $cat=intval($_POST['category_id']); $conn->query("UPDATE docs SET category_id=$cat WHERE id=$id"); audit($conn,'doc_move','doc',$id,'to_category_id='.$cat); out(['ok'=>true]); }
if($a==='deleteDoc' && is_admin()){ $id=intval($_POST['id']); $conn->query("DELETE FROM docs WHERE id=$id"); audit($conn,'doc_delete','doc',$id,null); out(['ok'=>true]); }
http_response_code(400); echo json_encode(['error'=>'Invalid action']);
?>