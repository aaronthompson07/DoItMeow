<?php include 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Task Tracker — Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .kpi-card .metric { font-size: 2rem; font-weight: 700; }
    .kpi-card .sub { color: #6c757d; }
    .column-card { min-height: 300px; }
    .task-card { transition: transform .05s ease-in; }
    .task-card:active { transform: scale(0.99); }
    .empty { color:#6c757d; font-style: italic; }
    .kicker { font-weight:600; letter-spacing:.02em; color:#6c757d; }
  </style>
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="mb-0">Dashboard</h1>
    <small class="text-muted">Auto-refreshes every minute</small>
  </div>

  <!-- KPI Cards -->
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="card kpi-card shadow-sm p-3">
        <div class="sub">Today's Tasks</div>
        <div class="metric" id="kpiTasksToday">—</div>
        <div class="sub"><span id="kpiTasksBreakdown">Morning/Mid/After</span></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card kpi-card shadow-sm p-3">
        <div class="sub">Completed Today</div>
        <div class="metric" id="kpiCompletedToday">—</div>
        <div class="sub"><a href="#" id="viewRecent">view recent</a></div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card kpi-card shadow-sm p-3">
        <div class="sub">Open Inventory Session</div>
        <div class="metric" id="kpiInvSession">—</div>
        <div class="sub" id="kpiInvSub">—</div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card kpi-card shadow-sm p-3">
        <div class="sub">Training Pending</div>
        <div class="metric" id="kpiTrainingPending">—</div>
        <div class="sub">All users (templates + custom)</div>
      </div>
    </div>
  </div>

  <!-- Recent Activity (hidden until clicked) -->
  <div class="card mb-3 d-none" id="recentCard">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Task Completions (Today)</h5>
        <button class="btn btn-sm btn-outline-secondary" id="hideRecent">Hide</button>
      </div>
      <div class="table-responsive mt-3">
        <table class="table table-striped mb-0">
          <thead><tr><th>Time</th><th>User</th><th>Task</th><th>Timeframe</th></tr></thead>
          <tbody id="recentRows"></tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Three Columns -->
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm column-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="kicker">Morning</div>
            <span class="badge text-bg-secondary" id="badgeMorning">0</span>
          </div>
          <div id="colMorning"></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm column-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="kicker">Mid-Day</div>
            <span class="badge text-bg-secondary" id="badgeMid">0</span>
          </div>
          <div id="colMid"></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm column-card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="kicker">Afternoon</div>
            <span class="badge text-bg-secondary" id="badgeAfter">0</span>
          </div>
          <div id="colAfter"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Complete Modal -->
<div class='modal fade' id='completeModal' tabindex='-1'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title'>Complete Task</h5>
        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
      </div>
      <div class='modal-body'>
        <label class="form-label">Select your name</label>
        <select id='userSelect' class='form-select mb-3'></select>
        <input type='hidden' id='taskId'>
      </div>
      <div class='modal-footer'>
        <button class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
        <button class='btn btn-primary' id='confirmComplete'>Mark Complete</button>
      </div>
    </div>
  </div>
</div>

<script>
function loadKPIs(){
  $.getJSON('api_dashboard.php?action=kpis', function(d){
    // Tasks
    $('#kpiTasksToday').text(d.tasks_today.total);
    $('#kpiTasksBreakdown').text(d.tasks_today.morning + ' / ' + d.tasks_today.midday + ' / ' + d.tasks_today.afternoon);
    // Completed today
    $('#kpiCompletedToday').text(d.completed_today.total);
    const tb = $('#recentRows').empty();
    (d.completed_today.recent || []).forEach(r => {
      tb.append('<tr><td>'+r.completed_at+'</td><td>'+r.user_name+'</td><td>'+r.title+'</td><td>'+r.timeframe+'</td></tr>');
    });
    // Inventory session
    if(d.inventory.open){
      $('#kpiInvSession').text('#' + d.inventory.id);
      $('#kpiInvSub').text('Started ' + d.inventory.started_at);
    } else {
      $('#kpiInvSession').text('None');
      $('#kpiInvSub').text('No open session');
    }
    // Training pending
    $('#kpiTrainingPending').text(d.training.pending_total || 0);
  });
}
$('#viewRecent').on('click', function(e){ e.preventDefault(); $('#recentCard').removeClass('d-none'); });
$('#hideRecent').on('click', function(){ $('#recentCard').addClass('d-none'); });

function renderTasks(tasks){
  const buckets = { 'Morning': [], 'Mid-Day': [], 'Afternoon': [] };
  tasks.forEach(t => { if (buckets[t.timeframe]) buckets[t.timeframe].push(t); });

  function build(list, targetSel, badgeSel){
    const target = $(targetSel).empty();
    $(badgeSel).text(list.length);
    if (!list.length){
      target.append('<div class="empty">No tasks.</div>');
      return;
    }
    list.forEach(t => {
      const card = $(`<div class="card task-card mb-2">
        <div class="card-body">
          <h6 class="card-title mb-1">${t.title}</h6>
          ${t.description ? `<div class="text-muted small mb-2">${t.description}</div>` : ''}
          <button class="btn btn-sm btn-success completeBtn" data-id="${t.id}">Mark Complete</button>
        </div>
      </div>`);
      target.append(card);
    });
  }

  build(buckets['Morning'], '#colMorning', '#badgeMorning');
  build(buckets['Mid-Day'], '#colMid', '#badgeMid');
  build(buckets['Afternoon'], '#colAfter', '#badgeAfter');
}

function loadTasks(){
  $.getJSON('api.php?action=getTasks', function(tasks){
    renderTasks(tasks);
  });
}

function loadUsers(){
  $.getJSON('api.php?action=getUsers', function(users){
    $('#userSelect').html(users.map(u => `<option value='${u.id}'>${u.name}</option>`));
  });
}

$(document).on('click', '.completeBtn', function(){
  $('#taskId').val($(this).data('id'));
  new bootstrap.Modal($('#completeModal')).show();
});

$('#confirmComplete').on('click', function(){
  $.post('api.php?action=markComplete', {
    task_id: $('#taskId').val(),
    user_id: $('#userSelect').val()
  }, function(){
    bootstrap.Modal.getInstance($('#completeModal')).hide();
    loadTasks(); // refresh lists
    loadKPIs();  // update KPI counts & recents
  });
});

$(function(){
  loadUsers();
  loadTasks();
  loadKPIs();
  setInterval(function(){ loadTasks(); loadKPIs(); }, 60000);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
