<?php include 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Reports</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>.preset-btns .btn{margin-right:.25rem;margin-bottom:.25rem}</style>
</head><body class="bg-light"><?php include 'header.php'; ?>
<div class="container">
  <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tasks" type="button">Task Completions</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#inventory" type="button">Inventory Snapshots</button></li>
  </ul>
  <div class="tab-content p-3 bg-white rounded-bottom shadow-sm">
    <div class="tab-pane fade show active" id="tasks">
      <div class="row g-2 align-items-end">
        <div class="col-md-2"><label class="form-label">Start</label><input type="date" id="tStart" class="form-control"></div>
        <div class="col-md-2"><label class="form-label">End</label><input type="date" id="tEnd" class="form-control"></div>
        <div class="col-md-3"><label class="form-label">User</label><select id="tUser" class="form-select"><option value="">All</option></select></div>
        <div class="col-md-3"><label class="form-label">Timeframe</label><select id="tFrame" class="form-select"><option value="">All</option><option>Morning</option><option>Mid-Day</option><option>Afternoon</option></select></div>
        <div class="col-md-2 preset-btns"><label class="form-label d-block">Presets</label>
          <button class="btn btn-sm btn-outline-secondary" data-p="today">Today</button>
          <button class="btn btn-sm btn-outline-secondary" data-p="yesterday">Yesterday</button>
          <button class="btn btn-sm btn-outline-secondary" data-p="7">Last 7d</button>
          <button class="btn btn-sm btn-outline-secondary" data-p="30">Last 30d</button>
        </div>
      </div>
      <div class="mt-3 d-flex gap-2">
        <button id="runTasks" class="btn btn-primary">Run</button>
        <a id="exportTasks" class="btn btn-outline-secondary" href="#">Export CSV</a>
        <a id="exportUserSummary" class="btn btn-outline-secondary" href="#">Export User Summary</a>
      </div>
      <div class="row mt-3">
        <div class="col-md-7">
          <h6>Results</h6>
          <table class="table table-striped"><thead><tr><th>Completed At</th><th>User</th><th>Task</th><th>Timeframe</th></thead><tbody id="tRows"></tbody></table>
        </div>
        <div class="col-md-5">
          <h6>Completions Over Time</h6>
          <canvas id="tChart" height="220"></canvas>
          <h6 class="mt-3">Per-User Summary</h6>
          <table class="table table-sm"><thead><tr><th>User</th><th>Total</th></tr></thead><tbody id="tUserSummary"></tbody></table>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="inventory">
      <div class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label">Session</label><select id="iSession" class="form-select"></select></div>
        <div class="col-md-3"><label class="form-label">Location</label><select id="iLoc" class="form-select"></select></div>
        <div class="col-md-6"><label class="form-label d-block">Exports</label>
          <a id="exportInv" class="btn btn-outline-secondary btn-sm" href="#">Export Snapshot CSV</a>
          <a id="exportAllInv" class="btn btn-outline-secondary btn-sm" href="#">Export ALL Sessions CSV</a>
        </div>
      </div>
      <div class="mt-3"><button id="runInv" class="btn btn-primary">Load Snapshot</button></div>
      <table class="table table-striped mt-3"><thead><tr><th>Item</th><th>Quantity</th></tr></thead><tbody id="iRows"></tbody></table>
    </div>
  </div>
</div>
<script>
let tChart=null; const fmt=d=>d.toISOString().slice(0,10);
function preset(p){ const today=new Date(); let s,e=new Date(); if(p==='today'){ s=new Date(); } else if(p==='yesterday'){ s=new Date(today); s.setDate(today.getDate()-1); e=new Date(s); } else { const n=parseInt(p,10); s=new Date(today); s.setDate(today.getDate()-n+1); } $('#tStart').val(fmt(s)); $('#tEnd').val(fmt(e)); }
function loadUsers(){ $.getJSON('api_reports.php?action=listUsers', u=> u.forEach(x=> $('#tUser').append(new Option(x.name,x.id)))); }
function runTasks(){ const q={ start:$('#tStart').val(), end:$('#tEnd').val(), user_id:$('#tUser').val(), timeframe:$('#tFrame').val() }; const params=$.param(q);
  $('#exportTasks').attr('href','api_reports.php?action=exportTaskCompletions&'+params); $('#exportUserSummary').attr('href','api_reports.php?action=exportTaskUserSummary&'+params);
  $.getJSON('api_reports.php?action=getTaskCompletions&'+params, rows=>{ const tb=$('#tRows').empty(); rows.forEach(r=> tb.append(`<tr><td>${r.completed_at}</td><td>${r.user_name}</td><td>${r.title}</td><td>${r.timeframe}</td></tr>`)); });
  $.getJSON('api_reports.php?action=getTaskUserSummary&'+params, rows=>{ const tb=$('#tUserSummary').empty(); rows.forEach(r=> tb.append(`<tr><td>${r.name}</td><td>${r.total}</td></tr>`)); });
  $.getJSON('api_reports.php?action=getTaskCompletionsTimeseries&'+params, rows=>{ const labels=rows.map(r=>r.d), data=rows.map(r=>parseInt(r.c,10)); if(tChart) tChart.destroy(); tChart=new Chart(document.getElementById('tChart').getContext('2d'), {type:'line', data:{labels, datasets:[{label:'Completions', data}]}, options:{responsive:true, maintainAspectRatio:false}}); });
}
function loadSessions(){ $.getJSON('api_reports.php?action=listSessions', s=>{ const sel=$('#iSession').empty(); s.forEach(x=> sel.append(new Option('#'+x.id+' ('+x.status+') '+x.started_at, x.id))); }); }
function loadLocations(){ $.getJSON('api_reports.php?action=listLocations', l=>{ const sel=$('#iLoc').empty(); sel.append(new Option('Select location','')); l.forEach(x=> sel.append(new Option(x.name,x.id))); }); }
function runInv(){ const sid=$('#iSession').val(), loc=$('#iLoc').val(); if(!sid){return;} $('#exportInv').attr('href','api_reports.php?action=exportInventorySnapshot&session_id='+sid+'&location_id='+(loc||'')); $('#exportAllInv').attr('href','api_reports.php?action=exportAllInventorySessions&location_id='+(loc||'')); if(!loc){ $('#iRows').html('<tr><td colspan=2><em>Select a location to view snapshot</em></td></tr>'); return; } $.getJSON('api_reports.php?action=getInventorySnapshot&session_id='+sid+'&location_id='+loc, rows=>{ const tb=$('#iRows').empty(); rows.forEach(r=> tb.append(`<tr><td>${r.name}</td><td>${r.quantity}</td></tr>`)); }); }
$(function(){ loadUsers(); loadSessions(); loadLocations(); preset('30'); $('#runTasks').on('click', runTasks); $('#runInv').on('click', runInv); $('[data-p]').on('click', function(){ preset($(this).data('p')); }); });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script></body></html>
