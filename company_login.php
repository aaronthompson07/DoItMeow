
<?php
require_once 'db.php';
require_once 'auth_company.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $conn->prepare("SELECT cc.id, cc.password_hash, c.id AS company_id, c.name AS company_name
                          FROM company_credentials cc
                          JOIN companies c ON c.id = cc.company_id
                          WHERE cc.username = ? LIMIT 1");
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();
  $row = $res->fetch_assoc();
  if ($row && password_verify($password, $row['password_hash'])) {
    set_company($row['company_id'], $row['company_name']);
    header('Location: index.php');
    exit;
  } else {
    $error = 'Invalid username or password';
  }
  $stmt->close();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Company Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:420px;">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="mb-3">Company Login</h4>
      <?php if ($error): ?><div class="alert alert-danger"><?=$error?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input class="form-control" name="username" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <button class="btn btn-primary w-100">Sign In</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
