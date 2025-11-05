<?php include 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Training</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>
<body class="bg-light"><?php include 'header.php'; ?>
<div class="container">
  <div class="row g-2 align-items-end"><div class="col-md-4">
    <label class="form-label">User</label><select id="viewUser" class="form-select"></select>
  </div></div>
  <div class="row mt-3">
    <div class="col-md-6"><h5>Template Assignments</h5>
      <table class="table table-striped"><thead><tr><th>Title</th><th>Assigned</th><th>Status</th></tr></thead><tbody id="tblTemplate"></tbody></table>
    </div>
    <div class="col-md-6"><h5>Custom Tasks</h5>
      <table class="table table-striped"><thead><tr><th>Title</th><th>Assigned</th><th>Status</th></tr></thead><tbody id="tblCustom"></tbody></table>
    </div>
  </div>
</div>
<script>
function loadUsers(){ $.getJSON('api_training.php?action=listUsers', rows=>{ const v=$('#viewUser').empty(); rows.forEach(r=> v.append(new Option(r.name,r.id))); if(rows.length){ $('#viewUser').val(rows[0].id).trigger('change'); } }); }
function loadUserTraining(){ const uid=$('#viewUser').val(); if(!uid) return; $.getJSON('api_training.php?action=listUserTraining&user_id='+uid, d=>{ const t=$('#tblTemplate').empty(); d.template.forEach(r=> t.append(`<tr><td>${r.title}</td><td>${r.assigned_at}</td><td>${r.completed_at? '✅ '+r.completed_at : '❌ Pending'}</td></tr>`)); const c=$('#tblCustom').empty(); d.custom.forEach(r=> c.append(`<tr><td>${r.title}</td><td>${r.assigned_at}</td><td>${r.completed_at? '✅ '+r.completed_at : '❌ Pending'}</td></tr>`)); }); }
$(function(){ loadUsers(); $('#viewUser').on('change', loadUserTraining); });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>
