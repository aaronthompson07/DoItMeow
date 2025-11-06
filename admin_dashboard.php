<?php require_once 'admin_guard.php'; ?>
<?php require_once 'auth.php'; require_admin(); include 'db.php'; ?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container mt-4">
  <h1 class="mb-3">Admin Dashboard</h1>
  <div class="row g-3">
    <div class="col-md-3">
      <a class="text-decoration-none" href="admin_users.php">
        <div class="card shadow-sm p-3 h-100">
          <h5 class="mb-1">Manage Users</h5>
          <div class="text-muted small">Create, edit, disable, delete</div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a class="text-decoration-none" href="admin.php">
        <div class="card shadow-sm p-3 h-100">
          <h5 class="mb-1">Manage Tasks</h5>
          <div class="text-muted small">Create / assign / edit</div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a class="text-decoration-none" href="inventory_admin.php">
        <div class="card shadow-sm p-3 h-100">
          <h5 class="mb-1">Inventory Admin</h5>
          <div class="text-muted small">Items, locations, sessions</div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a class="text-decoration-none" href="manage_docs.php">
        <div class="card shadow-sm p-3 h-100">
          <h5 class="mb-1">Documentation</h5>
          <div class="text-muted small">Pages, categories, TinyMCE</div>
        </div>
      </a>
    </div>
    <div class="col-md-3">
      <a class="text-decoration-none" href="admin_audit.php">
        <div class="card shadow-sm p-3 h-100">
          <h5 class="mb-1">Admin Audit</h5>
          <div class="text-muted small">View full audit trail</div>
        </div>
      </a>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
