<?php
require_once 'auth.php';
require_once 'db.php';
require_once 'audit.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pwd = $_POST['password'] ?? '';
  if ($pwd === ADMIN_PASSWORD) {
    $_SESSION['is_admin'] = true;
    audit($conn, 'login', 'admin', null, 'success');
    $to = $_GET['redirect'] ?? 'admin_dashboard.php';
    header("Location: $to");
    exit;
  } else {
    audit($conn, 'login', 'admin', null, 'failed');
    $error = 'Invalid password';
  }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include 'header.php'; ?>
<div class="container" style="max-width:420px;">
  <div class="card shadow-sm mt-5">
    <div class="card-body">
      <h4 class="mb-3">Admin Login</h4>
      <?php if ($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password"></div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
