<?php
include 'db.php'; include 'audit.php'; header('Content-Type: application/json'); $a=$_GET['action']??'';
function out($d){ echo json_encode($d); exit; }
function is_admin(){ if (session_status()===PHP_SESSION_NONE) session_start(); return !empty($_SESSION['is_admin']); }

if($a==='getLocations'){ $r=$conn->query("SELECT id,name FROM inventory_locations ORDER BY name"); out($r->fetch_all(MYSQLI_ASSOC)); }
if($a==='addLocation' && is_admin()){ $name=$conn->real_escape_string($_POST['name']); $conn->query("INSERT INTO inventory_locations(name) VALUES('$name')"); audit($conn,'inv_location_add','inventory_location',$conn->insert_id,$name); out(['ok'=>true]); }
if($a==='getItemsByLocation'){ $loc=intval($_GET['location_id']); $r=$conn->query("SELECT id,name FROM inventory_items WHERE location_id=$loc ORDER BY name"); out($r->fetch_all(MYSQLI_ASSOC)); }
if($a==='addItem' && is_admin()){ $name=$conn->real_escape_string($_POST['name']); $loc=intval($_POST['location_id']); $conn->query("INSERT INTO inventory_items(name,location_id) VALUES('$name',$loc)"); audit($conn,'inv_item_add','inventory_item',$conn->insert_id,"loc=$loc;name=$name"); out(['ok'=>true]); }

if($a==='getCurrentSession'){ $r=$conn->query("SELECT id,started_at,status FROM inventory_counts WHERE status='open' ORDER BY started_at DESC LIMIT 1"); $row=$r->fetch_assoc(); out($row?:null); }

// PUBLIC: start/close sessions
if($a==='startSession'){ $conn->query("INSERT INTO inventory_counts(status) VALUES ('open')"); $id=$conn->insert_id; audit($conn,'inv_session_start','inventory_count',$id,null); out(['ok'=>true,'id'=>$id]); }
if($a==='closeSession'){
  $sid=intval($_POST['session_id']);
  // Fill blanks from previous snapshot
  $items=$conn->query("SELECT id FROM inventory_items");
  while($it=$items->fetch_assoc()){
    $iid=intval($it['id']);
    $chk=$conn->query("SELECT id FROM inventory_entries WHERE count_id=$sid AND item_id=$iid");
    if(!$chk->num_rows){
      $q=$conn->query("SELECT ie.quantity FROM inventory_entries ie JOIN inventory_counts ic ON ic.id=ie.count_id WHERE ie.item_id=$iid AND ic.id<>$sid ORDER BY ic.started_at DESC, ie.id DESC LIMIT 1");
      $qty=0; if($r=$q->fetch_assoc()) $qty=intval($r['quantity']);
      $conn->query("INSERT INTO inventory_entries(count_id,item_id,quantity) VALUES($sid,$iid,$qty)");
    }
  }
  $conn->query("UPDATE inventory_counts SET status='closed', closed_at=NOW() WHERE id=$sid");
  audit($conn,'inv_session_close','inventory_count',$sid,null);
  out(['ok'=>true]);
}

if($a==='getLatestCountsByLocation'){ $loc=intval($_GET['location_id']); $items=$conn->query("SELECT id,name FROM inventory_items WHERE location_id=$loc ORDER BY name"); $res=[]; while($it=$items->fetch_assoc()){ $iid=intval($it['id']); $q=$conn->query("SELECT ie.quantity FROM inventory_entries ie JOIN inventory_counts ic ON ic.id=ie.count_id WHERE ie.item_id=$iid ORDER BY ic.started_at DESC, ie.id DESC LIMIT 1"); $qty=0; if($r=$q->fetch_assoc()) $qty=intval($r['quantity']); $res[]=['item_id'=>$iid,'item_name'=>$it['name'],'quantity'=>$qty]; } out($res); }
if($a==='updateCount' && $_SERVER['REQUEST_METHOD']==='POST'){ $sid=intval($_POST['session_id']); $iid=intval($_POST['item_id']); $qty=intval($_POST['quantity']); $chk=$conn->query("SELECT id FROM inventory_entries WHERE count_id=$sid AND item_id=$iid"); if($chk->num_rows){ $id=intval($chk->fetch_assoc()['id']); $conn->query("UPDATE inventory_entries SET quantity=$qty WHERE id=$id"); } else { $conn->query("INSERT INTO inventory_entries(count_id,item_id,quantity) VALUES($sid,$iid,$qty)"); } audit($conn,'inv_update_count','inventory_entry',$iid,"session=$sid;qty=$qty"); out(['ok'=>true]); }

if($a==='listSessions'){ $r=$conn->query("SELECT id,started_at,closed_at,status FROM inventory_counts ORDER BY started_at DESC"); out($r->fetch_all(MYSQLI_ASSOC)); }
if($a==='getSnapshot'){ $sid=intval($_GET['session_id']); $loc=intval($_GET['location_id']); $r=$conn->query("SELECT i.id as item_id,i.name,COALESCE(ie.quantity,0) quantity FROM inventory_items i LEFT JOIN inventory_entries ie ON ie.item_id=i.id AND ie.count_id=$sid WHERE i.location_id=$loc ORDER BY i.name"); $rows=[]; while($x=$r->fetch_assoc()) $rows[]=$x; out($rows); }
if($a==='updateSnapshotEntry' && is_admin()){ $sid=intval($_POST['session_id']); $iid=intval($_POST['item_id']); $qty=intval($_POST['quantity']); $chk=$conn->query("SELECT id FROM inventory_entries WHERE count_id=$sid AND item_id=$iid"); if($chk->num_rows){ $id=intval($chk->fetch_assoc()['id']); $conn->query("UPDATE inventory_entries SET quantity=$qty WHERE id=$id"); } else { $conn->query("INSERT INTO inventory_entries(count_id,item_id,quantity) VALUES($sid,$iid,$qty)"); } audit($conn,'inv_snapshot_edit','inventory_entry',$iid,"session=$sid;qty=$qty"); out(['ok'=>true]); }

http_response_code(400); echo json_encode(['error'=>'Invalid action']);
?>