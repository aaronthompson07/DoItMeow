<?php require_once 'auth.php'; require_admin(); include 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Users Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .row-deleted { opacity:.6 }
    .pin { font-family: monospace; letter-spacing: 2px; }
  </style>
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container">
  <div class="d-flex align-items-center justify-content-between mt-3 mb-2">
    <h2 class="mb-0">Users</h2>
    <a class="btn btn-outline-secondary" href="admin_dashboard.php">← Admin Dashboard</a>
  </div>

  <div class="card p-3 mb-4">
    <h5>Add User</h5>
    <div class="row g-2 align-items-end">
      <div class="col-md-3"><label class="form-label">First name</label><input class="form-control" id="nu_first" required></div>
      <div class="col-md-3"><label class="form-label">Last name</label><input class="form-control" id="nu_last" required></div>
      <div class="col-md-2">
        <label class="form-label">PIN</label>
        <input class="form-control" id="nu_pin" placeholder="6-digit PIN" inputmode="numeric" pattern="\d{6}" minlength="6" maxlength="6" required>
        <div class="form-text">Exactly 6 digits</div>
      </div>
      <div class="col-md-2">
        <div class="form-check mt-4">
          <input class="form-check-input" type="checkbox" id="nu_is_admin">
          <label class="form-check-label" for="nu_is_admin">Admin?</label>
        </div>
      </div>
      <div class="col-md-2 d-grid"><button class="btn btn-primary" id="createUser">Add</button></div>
    </div>
  </div>

  <div class="card p-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h5 class="mb-0">All Users</h5>
      <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="toggleDeleted" checked>
        <label class="form-check-label" for="toggleDeleted">Show deleted</label>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
          <tr><th>Name</th><th>PIN</th><th>Role</th><th>Status</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody id="userRows"></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="eu_id">
        <div class="mb-2"><label class="form-label">First name</label><input class="form-control" id="eu_first" required></div>
        <div class="mb-2"><label class="form-label">Last name</label><input class="form-control" id="eu_last" required></div>
        <div class="mb-2">
          <label class="form-label">PIN</label>
          <input class="form-control" id="eu_pin" inputmode="numeric" pattern="\d{6}" minlength="6" maxlength="6" placeholder="6 digits" required>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="eu_is_admin">
          <label class="form-check-label" for="eu_is_admin">Admin?</label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="saveUser">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
function isDisabledValue(v){ return v === 1 || v === '1' || v === true || v === 'true'; }
function isAdminValue(v){ return v === 1 || v === '1' || v === true || v === 'true'; }

function loadUsers(){
  const includeDeleted = $('#toggleDeleted').is(':checked') ? 1 : 0;
  $.getJSON('api_users.php?action=list&include_deleted=' + includeDeleted, function(rows){
    const tb = $('#userRows').empty();
    rows.forEach(u => {
      const name = (u.full_name && u.full_name.trim()!=='') ? u.full_name : (u.name || '');
      const isDeleted = !!u.deleted_at;
      const disabled = isDisabledValue(u.disabled);
      const isAdmin = isAdminValue(u.is_admin);
      const role = isAdmin ? '<span class="badge text-bg-primary">Admin</span>' : '<span class="badge text-bg-light text-dark">User</span>';
      const status = isDeleted ? '<span class="badge text-bg-secondary">Deleted</span>' : (disabled ? '<span class="badge text-bg-warning">Disabled</span>' : '<span class="badge text-bg-success">Active</span>');
      const actions = `
        <div class="btn-group">
          <button class="btn btn-sm btn-outline-primary editBtn" data-id="${u.id}" ${isDeleted ? 'disabled' : ''}>Edit</button>
          ${disabled ? `<button class="btn btn-sm btn-outline-success enableBtn" data-id="${u.id}" ${isDeleted?'disabled':''}>Enable</button>`
                      : `<button class="btn btn-sm btn-outline-warning disableBtn" data-id="${u.id}" ${isDeleted?'disabled':''}>Disable</button>`}
          ${isDeleted ? `<button class="btn btn-sm btn-outline-success restoreBtn" data-id="${u.id}">Restore</button>`
                      : `<button class="btn btn-sm btn-outline-danger deleteBtn" data-id="${u.id}">Delete</button>`}
        </div>`;
      tb.append(`<tr class="${isDeleted?'row-deleted':''}">
        <td>${name}</td>
        <td class="pin">${u.pin===null ? '' : u.pin}</td>
        <td>${role}</td>
        <td>${status}</td>
        <td class="text-end">${actions}</td>
      </tr>`);
    });
  });
}

$('#createUser').on('click', function(){
  const first = $('#nu_first').val().trim();
  const last  = $('#nu_last').val().trim();
  const pin   = $('#nu_pin').val().trim();
  const is_admin = $('#nu_is_admin').is(':checked') ? 1 : 0;
  if (!/^\d{6}$/.test(pin)) { alert('PIN must be exactly 6 digits'); return; }
  $.post('api_users.php?action=create', {first_name:first, last_name:last, pin:pin, is_admin:is_admin}, function(){
    $('#nu_first').val(''); $('#nu_last').val(''); $('#nu_pin').val(''); $('#nu_is_admin').prop('checked', false);
    loadUsers();
  }, 'json').fail(function(xhr){ alert('Create failed: ' + (xhr.responseJSON?.error || xhr.responseText)); });
});

$(document).on('click', '.editBtn', function(){
  const id = $(this).data('id');
  $.getJSON('api_users.php?action=list&include_deleted=1', function(rows){
    const u = rows.find(r => r.id == id);
    if (!u) return;
    $('#eu_id').val(u.id);
    $('#eu_first').val(u.first_name || '');
    $('#eu_last').val(u.last_name || '');
    $('#eu_pin').val(u.pin || '');
    $('#eu_is_admin').prop('checked', isAdminValue(u.is_admin));
    new bootstrap.Modal($('#editUserModal')).show();
  });
});

$('#saveUser').on('click', function(){
  const id = $('#eu_id').val();
  const first = $('#eu_first').val().trim();
  const last  = $('#eu_last').val().trim();
  const pin   = $('#eu_pin').val().trim();
  const is_admin = $('#eu_is_admin').is(':checked') ? 1 : 0;
  if (!/^\d{6}$/.test(pin)) { alert('PIN must be exactly 6 digits'); return; }
  $.post('api_users.php?action=update', {id, first_name:first, last_name:last, pin:pin, is_admin:is_admin}, function(){
    bootstrap.Modal.getInstance($('#editUserModal')).hide();
    loadUsers();
  }, 'json').fail(function(xhr){ alert('Update failed: ' + (xhr.responseJSON?.error || xhr.responseText)); });
});

$(document).on('click', '.disableBtn', function(){
  $.post('api_users.php?action=disable', {id: $(this).data('id')}, function(){ loadUsers(); }, 'json');
});
$(document).on('click', '.enableBtn', function(){
  $.post('api_users.php?action=enable', {id: $(this).data('id')}, function(){ loadUsers(); }, 'json');
});
$(document).on('click', '.deleteBtn', function(){
  if(!confirm('Soft delete this user?')) return;
  $.post('api_users.php?action=softDelete', {id: $(this).data('id')}, function(){ loadUsers(); }, 'json');
});
$(document).on('click', '.restoreBtn', function(){
  $.post('api_users.php?action=restore', {id: $(this).data('id')}, function(){ loadUsers(); }, 'json');
});

$(function(){
  loadUsers();
  $('#toggleDeleted').on('change', loadUsers);
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
