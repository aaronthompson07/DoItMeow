<?php require_once 'admin_guard.php'; include 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tasks Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container">
  <div class="mt-3 mb-2 d-flex justify-content-between align-items-center">
    <h2 class="mb-0">Tasks Admin</h2>
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="toggleDeleted">
      <label class="form-check-label" for="toggleDeleted">Show deleted</label>
    </div>
  </div>

  <div class="card p-3 mb-4 mt-2">
    <h5>Add Task</h5>
    <form id="addTaskForm" onsubmit="return false;">
      <div class="row g-2">
        <div class="col-md-4"><input class="form-control" name="title" placeholder="Title" required></div>
        <div class="col-md-4"><input class="form-control" name="description" placeholder="Description"></div>
        <div class="col-md-2">
          <select class="form-select" name="timeframe">
            <option>Morning</option><option>Mid-Day</option><option>Afternoon</option><option>Anytime</option>
          </select>
        </div>
        <div class="col-md-2">
          <select class="form-select" name="type">
            <option value="single">Single</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
          </select>
        </div>
        <div class="col-md-3"><label class="form-label small mb-0">Start</label><input type="date" class="form-control" name="start_date" value="<?=date('Y-m-d')?>"></div>
        <div class="col-md-3"><label class="form-label small mb-0">End (optional)</label><input type="date" class="form-control" name="end_date"></div>
        <div class="col-md-3">
          <label class="form-label small mb-0">Assigned User (optional)</label>
          <select class="form-select" name="assigned_user_id" id="assigned_user_id">
            <option value="">— Unassigned —</option>
            <?php $u=$conn->query("SELECT id, TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) AS n FROM users WHERE company_id=".$_SESSION['company_id']." AND disabled=0 AND deleted_at IS NULL ORDER BY last_name, first_name"); while($r=$u->fetch_assoc()){ echo '<option value="'.$r['id'].'">'.htmlspecialchars($r['n']).'</option>'; } ?>
          </select>
        </div>
        <div class="col-md-3 d-grid"><label class="form-label small mb-0">&nbsp;</label><button class="btn btn-primary" id="btnAdd">Add Task</button></div>
      </div>
    </form>
  </div>

  <div class="card p-3 mb-4">
    <h5>Existing Tasks</h5>
    <div class="table-responsive">
      <table class="table table-sm align-middle" id="tasksTable">
        <thead><tr><th>Title</th><th>Timeframe</th><th>Type</th><th>Start</th><th>End</th><th>Assigned To</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editTaskForm" onsubmit="return false;">
          <input type="hidden" name="id" id="et_id">
          <div class="row g-2">
            <div class="col-md-6"><label class="form-label">Title</label><input class="form-control" name="title" id="et_title" required></div>
            <div class="col-md-6"><label class="form-label">Description</label><input class="form-control" name="description" id="et_description"></div>
            <div class="col-md-4"><label class="form-label">Timeframe</label>
              <select class="form-select" name="timeframe" id="et_timeframe">
                <option>Morning</option><option>Mid-Day</option><option>Afternoon</option><option>Anytime</option>
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Type</label>
              <select class="form-select" name="type" id="et_type">
                <option value="single">Single</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
              </select>
            </div>
            <div class="col-md-4"><label class="form-label">Assigned User (optional)</label>
              <select class="form-select" name="assigned_user_id" id="et_assigned_user_id">
                <option value="">— Unassigned —</option>
                <?php $u2=$conn->query("SELECT id, TRIM(CONCAT(COALESCE(first_name,''),' ',COALESCE(last_name,''))) AS n FROM users WHERE company_id=".$_SESSION['company_id']." AND disabled=0 AND deleted_at IS NULL ORDER BY last_name, first_name"); while($r2=$u2->fetch_assoc()){ echo '<option value="'.$r2['id'].'">'.htmlspecialchars($r2['n']).'</option>'; } ?>
              </select>
            </div>
            <div class="col-md-6"><label class="form-label">Start</label><input type="date" class="form-control" name="start_date" id="et_start_date"></div>
            <div class="col-md-6"><label class="form-label">End (optional)</label><input type="date" class="form-control" name="end_date" id="et_end_date"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Cancel</button>
        <button class="btn btn-primary" id="saveEditTask" type="button">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script>
function loadTasks(){
  const showDeleted = document.getElementById('toggleDeleted').checked ? 1 : 0;
  $.getJSON('api_admin_mt.php?action=listTasks&include_deleted='+showDeleted, function(rows){
    const tb = $('#tasksTable tbody').empty();
    rows.forEach(t => {
      const status = t.deleted_at ? '<span class="badge text-bg-secondary">Deleted</span>' : '<span class="badge text-bg-success">Active</span>';
      const actions = `
        <div class="btn-group">
          <button class="btn btn-sm btn-outline-primary editBtn" data-id="${t.id}" ${t.deleted_at?'disabled':''}>Edit</button>
          ${t.deleted_at
            ? `<button class="btn btn-sm btn-outline-success restoreBtn" data-id="${t.id}">Restore</button>`
            : `<button class="btn btn-sm btn-outline-danger softDeleteBtn" data-id="${t.id}">Soft Delete</button>`}
        </div>`;
      tb.append(`<tr>
        <td>${escapeHtml(t.title)}</td>
        <td>${escapeHtml(t.timeframe)}</td>
        <td>${escapeHtml(t.type)}</td>
        <td>${t.start_date || ''}</td>
        <td>${t.end_date || ''}</td>
        <td>${t.assigned_user_name ? escapeHtml(t.assigned_user_name) : ''}</td>
        <td>${status}</td>
        <td class="text-end">${actions}</td>
      </tr>`);
    });
  });
}

function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' }[m])); }

// Add Task
$('#btnAdd').on('click', function(){
  const data = $('#addTaskForm').serialize();
  $.post('api_admin_mt.php?action=createTask', data, function(){ 
    $('#addTaskForm')[0].reset();
    loadTasks();
  }).fail(function(xhr){ alert('Create failed: ' + (xhr.responseText || '')); });
});

// Edit open
$(document).on('click', '.editBtn', function(e){
  e.preventDefault();
  const id = $(this).data('id');
  $.getJSON('api_admin_mt.php?action=getTask&id='+id, function(t){
    if(!t) return;
    $('#et_id').val(t.id);
    $('#et_title').val(t.title);
    $('#et_description').val(t.description);
    $('#et_timeframe').val(t.timeframe);
    $('#et_type').val(t.type);
    $('#et_assigned_user_id').val(t.assigned_user_id);
    $('#et_start_date').val(t.start_date);
    $('#et_end_date').val(t.end_date);
    new bootstrap.Modal($('#editTaskModal')).show();
  });
});

// Edit save
$('#saveEditTask').on('click', function(e){
  e.preventDefault();
  const data = $('#editTaskForm').serialize();
  $.post('api_admin_mt.php?action=updateTask', data, function(){
    loadTasks();
    const m = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
    if (m) m.hide();
  }).fail(function(xhr){ alert('Save failed: ' + xhr.responseText); });
});

// Soft delete
$(document).on('click', '.softDeleteBtn', function(){
  if(!confirm('Soft delete this task?')) return;
  $.post('api_admin_mt.php?action=softDelete', {id: $(this).data('id')}, function(){ loadTasks(); });
});

// Restore
$(document).on('click', '.restoreBtn', function(){
  $.post('api_admin_mt.php?action=restore', {id: $(this).data('id')}, function(){ loadTasks(); });
});

$(function(){
  loadTasks();
  $('#toggleDeleted').on('change', loadTasks);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
