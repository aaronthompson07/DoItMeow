<?php require_once 'auth.php'; require_admin(); include 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Inventory Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>
<body class="bg-light"><?php include 'header.php'; ?>
<div class="container">
  <div class="row g-4">
    <div class="col-md-6"><div class="card p-3">
      <h5>Add Location</h5>
      <div class="input-group"><input id="locName" class="form-control" placeholder="Location name"><button id="addLoc" class="btn btn-primary">Add</button></div>
      <ul id="locList" class="list-group mt-3"></ul>
    </div></div>
    <div class="col-md-6"><div class="card p-3">
      <h5>Add Item</h5>
      <div class="row g-2"><div class="col-6"><input id="itemName" class="form-control" placeholder="Item name"></div><div class="col-6"><select id="itemLoc" class="form-select"></select></div></div>
      <button id="addItem" class="btn btn-primary mt-2">Add Item</button>
      <div class="mt-3"><h6>Items by Location</h6><select id="viewLoc" class="form-select mb-2"></select><ul id="itemList" class="list-group"></ul></div>
    </div></div>
  </div>
  <hr>
  <div class="row g-3">
    <div class="col-md-6">
      <h5>Count Sessions</h5>
      <div class="d-flex gap-2"><button id="startSession" class="btn btn-success btn-sm">Start New Session</button><button id="closeSession" class="btn btn-warning btn-sm">Close Current Session</button></div>
      <div class="mt-2"><span id="curSession" class="badge bg-secondary">Checking...</span></div>
    </div>
    <div class="col-md-6">
      <h5>Modify Old Snapshot</h5>
      <div class="row g-2">
        <div class="col-4"><select id="snapSession" class="form-select"></select></div>
        <div class="col-4"><select id="snapLoc" class="form-select"></select></div>
        <div class="col-4"><button id="loadSnap" class="btn btn-primary w-100">Load</button></div>
      </div>
      <table class="table table-sm mt-2"><thead><tr><th>Item</th><th>Qty</th><th>Update</th></tr></thead><tbody id="snapRows"></tbody></table>
    </div>
  </div>
</div>
<script>
function refreshLocations(selectors){ $.getJSON('api_inventory.php?action=getLocations', function(l){ selectors.forEach(s=>{ const sel=$(s).empty(); l.forEach(x=> sel.append(new Option(x.name,x.id))); }); }); }
function refreshItems(){ const loc=$('#viewLoc').val(); if(!loc) return; $.getJSON('api_inventory.php?action=getItemsByLocation&location_id='+loc, function(items){ const ul=$('#itemList').empty(); items.forEach(i=> ul.append(`<li class='list-group-item'>${i.name}</li>`)); }); }
function currentSession(){ $.getJSON('api_inventory.php?action=getCurrentSession', function(s){ $('#curSession').text(s?('Open #'+s.id):'No open session'); }); }
$(function(){
  refreshLocations(['#itemLoc','#viewLoc','#snapLoc']); currentSession();
  $('#addLoc').on('click', ()=> $.post('api_inventory.php?action=addLocation', {name: $('#locName').val()}, ()=>{ $('#locName').val(''); refreshLocations(['#itemLoc','#viewLoc','#snapLoc']); }));
  $('#viewLoc').on('change', refreshItems);
  $('#addItem').on('click', ()=> $.post('api_inventory.php?action=addItem', {name: $('#itemName').val(), location_id: $('#itemLoc').val()}, ()=>{ $('#itemName').val(''); refreshItems(); }));
  $('#startSession').on('click', ()=> $.post('api_inventory.php?action=startSession', {}, ()=> currentSession()));
  $('#closeSession').on('click', ()=> $.getJSON('api_inventory.php?action=getCurrentSession', function(s){ if(!s){ alert('No open session'); return; } $.post('api_inventory.php?action=closeSession', {session_id: s.id}, ()=> currentSession()); }));
  // snapshots
  $.getJSON('api_inventory.php?action=listSessions', function(s){ const sel=$('#snapSession').empty(); s.forEach(x=> sel.append(new Option('#'+x.id+' ('+x.status+') '+x.started_at, x.id))); });
  $('#loadSnap').on('click', function(){ const sid=$('#snapSession').val(), loc=$('#snapLoc').val(); if(!sid||!loc) return; $.getJSON('api_inventory.php?action=getSnapshot&session_id='+sid+'&location_id='+loc, function(rows){ const tb=$('#snapRows').empty(); rows.forEach(r=>{ const tr=$('<tr/>'); tr.append(`<td>${r.name}</td>`); const input=$('<input type="number" min="0" class="form-control form-control-sm" />').val(r.quantity); const btn=$('<button class="btn btn-sm btn-outline-primary">Save</button>').on('click', ()=> $.post('api_inventory.php?action=updateSnapshotEntry', {session_id:sid,item_id:r.item_id,quantity:input.val()}, ()=>{})); tr.append($('<td/>').append(input)); tr.append($('<td/>').append(btn)); tb.append(tr); }); }); });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>
