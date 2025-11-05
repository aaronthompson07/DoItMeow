<?php require_once 'auth.php'; require_admin(); ?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>.admin-card{min-height:130px}</style></head>
<body class="bg-light"><?php include 'header.php'; ?>
<div class="container"><h2 class="mb-4">Admin Dashboard</h2>
  <div class="row g-3">
    <div class="col-md-4"><a href="admin.php" class="text-decoration-none">
      <div class="card admin-card shadow-sm p-3"><h5>Tasks Admin</h5><p class="text-muted mb-0">Manage tasks & users.</p></div></a></div>
    <div class="col-md-4"><a href="manage_docs.php" class="text-decoration-none">
      <div class="card admin-card shadow-sm p-3"><h5>Documentation Admin</h5><p class="text-muted mb-0">Categories & pages.</p></div></a></div>
    <div class="col-md-4"><a href="inventory_admin.php" class="text-decoration-none">
      <div class="card admin-card shadow-sm p-3"><h5>Inventory Admin</h5><p class="text-muted mb-0">Locations, items, sessions.</p></div></a></div>
    <div class="col-md-4"><a href="training_admin.php" class="text-decoration-none">
      <div class="card admin-card shadow-sm p-3"><h5>Training Admin</h5><p class="text-muted mb-0">Job types & templates.</p></div></a></div>
    <div class="col-md-4"><a href="reports.php" class="text-decoration-none">
      <div class="card admin-card shadow-sm p-3"><h5>Reports</h5><p class="text-muted mb-0">Tasks & inventory CSVs.</p></div></a></div>
    <div class="col-md-4"><a href="admin_audit.php" class="text-decoration-none">
      <div class="card admin-card shadow-sm p-3"><h5>Audit Log</h5><p class="text-muted mb-0">Full activity trail with CSV export.</p></div></a></div>
  </div></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
