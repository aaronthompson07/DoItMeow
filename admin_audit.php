<?php require_once 'auth.php'; require_admin(); include 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Audit Log</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container">
  <h2 class="mb-3">Audit Log</h2>
  <form class="row g-2 mb-3" method="get">
    <div class="col-md-2"><input type="date" name="start" class="form-control" value="<?=htmlspecialchars($_GET['start'] ?? '')?>" placeholder="Start"></div>
    <div class="col-md-2"><input type="date" name="end" class="form-control" value="<?=htmlspecialchars($_GET['end'] ?? '')?>" placeholder="End"></div>
    <div class="col-md-3"><input type="text" name="action" class="form-control" value="<?=htmlspecialchars($_GET['action'] ?? '')?>" placeholder="Action contains"></div>
    <div class="col-md-3"><input type="text" name="entity" class="form-control" value="<?=htmlspecialchars($_GET['entity'] ?? '')?>" placeholder="Entity contains"></div>
    <div class="col-md-2 d-grid"><button class="btn btn-primary">Filter</button></div>
  </form>
  <div class="mb-2">
    <a class="btn btn-outline-secondary btn-sm" href="export_audit.php?<?=http_build_query($_GET)?>">Export CSV</a>
  </div>
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead><tr><th>When</th><th>Actor</th><th>Action</th><th>Entity</th><th>Entity ID</th><th>IP</th><th>Details</th></tr></thead>
      <tbody>
        <?php
          $w=[];
          if (!empty($_GET['start'])) $w[]="happened_at >= '".$conn->real_escape_string($_GET['start'])." 00:00:00'";
          if (!empty($_GET['end'])) $w[]="happened_at <= '".$conn->real_escape_string($_GET['end'])." 23:59:59'";
          if (!empty($_GET['action'])) $w[]="action LIKE '%".$conn->real_escape_string($_GET['action'])."%'";
          if (!empty($_GET['entity'])) $w[]="entity LIKE '%".$conn->real_escape_string($_GET['entity'])."%'";
          $where = $w ? ('WHERE '.implode(' AND ', $w)) : '';
          $sql = "SELECT * FROM audit_log $where ORDER BY happened_at DESC, id DESC LIMIT 1000";
          $res = $conn->query($sql);
          while($row=$res->fetch_assoc()){
            echo '<tr>';
            echo '<td>'.htmlspecialchars($row['happened_at']).'</td>';
            echo '<td>'.htmlspecialchars($row['actor']).'</td>';
            echo '<td>'.htmlspecialchars($row['action']).'</td>';
            echo '<td>'.htmlspecialchars($row['entity']).'</td>';
            echo '<td>'.htmlspecialchars($row['entity_id']).'</td>';
            echo '<td>'.htmlspecialchars($row['ip']).'</td>';
            echo '<td><pre class="mb-0" style="white-space:pre-wrap">'.htmlspecialchars($row['details']).'</pre></td>';
            echo '</tr>';
          }
        ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
