<?php require_once 'auth_company.php'; require_company(); include 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>DoItMeow • Today</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { background:#f8f9fa; }
    .kpi .value { font-size: 2rem; font-weight: 800; line-height: 1; }
    .kpi .sub { font-size:.9rem; color:#6c757d; }
    .bucket-card { border:0; box-shadow: 0 1px 6px rgba(0,0,0,.06); }
    .task-card { border-left:4px solid #0d6efd; }
    .task-card[data-t="Mid-Day"] { border-left-color:#20c997; }
    .task-card[data-t="Afternoon"] { border-left-color:#fd7e14; }
    .task-card[data-t="Anytime"] { border-left-color:#6c757d; }
    .task-title { font-weight:600; }
    .task-desc { color:#6c757d; font-size:.9rem; margin-bottom:6px; white-space:pre-wrap; word-break:break-word; }
  </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container my-3">

  <!-- KPI ROW -->
  <div class="row g-3 mb-3">
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card kpi">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <div class="sub">Total Tasks Today</div>
            <div class="value" id="kpiTotal">0</div>
          </div>
          <div class="text-end">
            <div class="sub">M / Mid / Aft / Any</div>
            <div id="kpiTotalBreakdown" class="fw-semibold">0 / 0 / 0 / 0</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card kpi">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <div class="sub">Completed Today</div>
            <div class="value" id="kpiCompleted">0</div>
          </div>
          <a class="small" data-bs-toggle="collapse" href="#completedTodayPanel">View recent</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card kpi">
        <div class="card-body">
          <div class="sub">Open Inventory Sessions</div>
          <div class="value" id="kpiInvOpen">0</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-xl-3">
      <div class="card kpi">
        <div class="card-body">
          <div class="sub">Trainings Pending</div>
          <div class="value" id="kpiTrainPending">0</div>
        </div>
      </div>
    </div>
  </div>

  <!-- View Recent (collapsible) -->
  <div class="collapse mb-3" id="completedTodayPanel">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Completed Today</strong>
        <button class="btn btn-sm btn-outline-secondary" id="refreshCompleted">Refresh</button>
      </div>
      <div class="list-group list-group-flush" id="completedList">
        <div class="list-group-item text-muted">No completions yet today.</div>
      </div>
    </div>
  </div>

  <!-- FOUR BUCKETS -->
  <div class="row g-3">
    <div class="col-lg-3">
      <div class="card bucket-card">
        <div class="card-header bg-white"><strong>Morning</strong></div>
        <div class="card-body vstack gap-2" id="colMorning"></div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card bucket-card">
        <div class="card-header bg-white"><strong>Mid-Day</strong></div>
        <div class="card-body vstack gap-2" id="colMid"></div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card bucket-card">
        <div class="card-header bg-white"><strong>Afternoon</strong></div>
        <div class="card-body vstack gap-2" id="colAft"></div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="card bucket-card">
        <div class="card-header bg-white"><strong>Anytime</strong></div>
        <div class="card-body vstack gap-2" id="colAny"></div>
      </div>
    </div>
  </div>
</div>

<script>
$.ajaxSetup({ cache:false });

function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' }[m])); }

function canAct(){ return !!(window.WHOAMI && window.WHOAMI.user); }

function renderTask(t){
  const disabled = canAct() ? '' : 'disabled';
  const el = $(`
    <div class="card task-card" data-t="${t.timeframe}">
      <div class="card-body py-2">
        <div class="task-title">${escapeHtml(t.title)}</div>
        <div class="task-desc">${escapeHtml(t.description || '')}</div>
        ${t.assigned_user_name ? `<div class="small mt-1"><span class="badge bg-light text-dark">Assigned: ${escapeHtml(t.assigned_user_name)}</span></div>` : ''}
        <div class="mt-2">
          <button class="btn btn-sm btn-success completeBtn" ${disabled} data-id="${t.id}">Mark Complete</button>
        </div>
      </div>
    </div>`);
  el.find('.completeBtn').on('click', function(){
    const taskId = $(this).data('id');
    $.post('api_mt.php?action=markComplete', {task_id: taskId}, function(){
      loadAll();
    }).fail(function(xhr){
      alert('Error: ' + (xhr.responseText || 'failed'));
    });
  });
  return el;
}

function loadTasks(){
  return $.getJSON('api_mt.php?action=getTasks&t='+Date.now(), function(rows){
    $('#colMorning,#colMid,#colAft,#colAny').empty();
    rows.forEach(t => {
      const card = renderTask(t);
      if (t.timeframe==='Morning') $('#colMorning').append(card);
      else if (t.timeframe==='Mid-Day') $('#colMid').append(card);
      else if (t.timeframe==='Afternoon') $('#colAft').append(card);
      else $('#colAny').append(card);
    });
  });
}

function loadCompleted(){
  return $.getJSON('api_mt.php?action=recentCompletions&t='+Date.now(), function(rows){
    const list = $('#completedList').empty();
    if (!rows.length){
      list.append('<div class="list-group-item text-muted">No completions yet today.</div>');
      return;
    }
    rows.forEach(r => {
      const item = $(`
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold">${escapeHtml(r.title || '(deleted task)')}</div>
            <div class="small text-muted">${escapeHtml(r.completed_by || 'Unknown')} • ${escapeHtml(r.timeframe || '')} • ${escapeHtml(r.completed_at)}</div>
          </div>
          <button class="btn btn-sm btn-outline-danger undoBtn" data-task="${r.task_id}">Undo</button>
        </div>`);
      item.find('.undoBtn').on('click', function(){
        const tid = $(this).data('task');
        $.post('api_mt.php?action=uncompleteTask', {task_id: tid}, function(){ loadAll(); });
      });
      list.append(item);
    });
  });
}

function loadKpis(){
  return $.getJSON('api_mt.php?action=dashboardStats&t='+Date.now(), function(s){
    const tb = s.total_by_timeframe || {};
    const totalAll = (tb.Morning||0)+(tb['Mid-Day']||0)+(tb.Afternoon||0)+(tb.Anytime||0);
    $('#kpiTotal').text(totalAll);
    $('#kpiTotalBreakdown').text(`${tb.Morning||0} / ${tb['Mid-Day']||0} / ${tb.Afternoon||0} / ${tb.Anytime||0}`);
    $('#kpiCompleted').text(s.completed_today ?? 0);
    $('#kpiInvOpen').text(s.open_inventory_sessions ?? 0);
    $('#kpiTrainPending').text(s.trainings_pending ?? 0);
  });
}

function loadAll(){ loadTasks(); loadCompleted(); loadKpis(); }

$(function(){
  // When header updates whoami, re-render buttons enable/disable by reloading tasks
  document.addEventListener('whoami:changed', function(){ loadTasks(); });

  $('#refreshCompleted').on('click', function(){ loadCompleted(); });

  loadAll();
  setInterval(function(){ loadAll(); }, 60000);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
