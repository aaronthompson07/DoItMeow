<?php
// Hardened admin PIN login page (auto-submit @ 6 digits, tablet keypad)
// Does NOT include header.php to avoid accidental require_admin() loops.

include 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
  $pin = $_POST['pin'] ?? '';
  $next = $_POST['next'] ?? 'admin_dashboard.php';
  $pin = trim($pin);
  if (!preg_match('/^[0-9]{6}$/', $pin)) {
    $error = 'PIN must be exactly 6 digits.';
  } else {
    $pin_e = $conn->real_escape_string($pin);
    $q = "SELECT id, first_name, last_name, name, is_admin, disabled, deleted_at
          FROM users
          WHERE pin='$pin_e'
          LIMIT 1";
    $res = $conn->query($q);
    if ($res && $res->num_rows){
      $u = $res->fetch_assoc();
      if (!empty($u['deleted_at']) || intval($u['disabled'])===1){
        $error = 'This user is disabled or deleted.';
      } elseif (intval($u['is_admin']) !== 1){
        $error = 'This PIN is valid but does not have admin access.';
      } else {
        // success
        $_SESSION['is_admin'] = 1;
        $_SESSION['admin_user_id'] = intval($u['id']);
        $_SESSION['admin_name'] = trim(($u['first_name'] ?? '') . ' ' . ($u['last_name'] ?? '')) ?: ($u['name'] ?? 'Admin');
        // Default redirect
        if (!isset($_POST['next']) && isset($_GET['next'])) { $_POST['next'] = $_GET['next']; }
        $dest = $_POST['next'] ?? 'admin_dashboard.php';
        header('Location: ' . $dest);
        exit;
      }
    } else {
      $error = 'Invalid PIN.';
    }
  }
}
$next = $_GET['next'] ?? ($_POST['next'] ?? 'admin_dashboard.php');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .keypad { display: grid; grid-template-columns: repeat(3, 1fr); gap: .75rem; }
    .keypad .btn { padding: 1.25rem 0; font-size: 1.25rem; }
    .pin-input { font-size: 2rem; letter-spacing: .5rem; text-align: center; }
    @media (min-width: 576px){
      .keypad .btn { padding: 1.5rem 0; font-size: 1.5rem; }
    }
  </style>
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h3 class="mb-3 text-center">Admin Login</h3>
          <?php if ($error): ?>
            <div class="alert alert-danger"><?=htmlspecialchars($error)?></div>
          <?php endif; ?>
          <form id="loginForm" method="post" autocomplete="off">
            <input type="hidden" name="next" value="<?=htmlspecialchars($next)?>">
            <div class="mb-3">
              <label class="form-label d-flex justify-content-between align-items-center">
                <span>Admin PIN</span><small class="text-muted">6 digits</small>
              </label>
              <input
                type="password"
                class="form-control pin-input"
                name="pin"
                id="pin"
                maxlength="6"
                pattern="\d{6}"
                inputmode="numeric"
                placeholder="••••••"
                aria-label="Enter 6-digit PIN"
                autofocus
                required>
            </div>

            <div class="keypad mb-3">
              <!-- Flipped layout: 7 8 9 / 4 5 6 / 1 2 3 / 0 -->
              <button type="button" class="btn btn-outline-secondary" data-key="7">7</button>
              <button type="button" class="btn btn-outline-secondary" data-key="8">8</button>
              <button type="button" class="btn btn-outline-secondary" data-key="9">9</button>
              <button type="button" class="btn btn-outline-secondary" data-key="4">4</button>
              <button type="button" class="btn btn-outline-secondary" data-key="5">5</button>
              <button type="button" class="btn btn-outline-secondary" data-key="6">6</button>
              <button type="button" class="btn btn-outline-secondary" data-key="1">1</button>
              <button type="button" class="btn btn-outline-secondary" data-key="2">2</button>
              <button type="button" class="btn btn-outline-secondary" data-key="3">3</button>
              <button type="button" class="btn btn-outline-secondary" data-key="0">0</button>
              <button type="button" class="btn btn-outline-danger" data-action="clear">Clear</button>
              <button type="button" class="btn btn-outline-warning" data-action="back">⌫</button>
            </div>

            <div class="d-grid gap-2">
              <!-- Keep the button enabled as a graceful fallback -->
              <button id="submitBtn" class="btn btn-primary">Sign In</button>
            </div>
          </form>
        </div>
      </div>
      <p class="text-center text-muted mt-3 small">Tap or type your PIN. Auto-submits after 6 digits.</p>
    </div>
  </div>
</div>

<script>
(function(){
  const pinInput = document.getElementById('pin');
  const form = document.getElementById('loginForm');
  const submitBtn = document.getElementById('submitBtn');
  const KEYS = Array.from(document.querySelectorAll('[data-key]'));
  const ACTIONS = Array.from(document.querySelectorAll('[data-action]'));
  let submitting = false;

  function trySubmit(){
    if (submitting) return;
    if (pinInput.value.length === 6){
      submitting = true;
      // Ensure the submit button isn't inhibiting submission
      submitBtn.disabled = false;
      form.submit(); // more broadly compatible than requestSubmit in older browsers
    }
  }

  pinInput.addEventListener('input', () => {
    pinInput.value = pinInput.value.replace(/\D+/g, '').slice(0, 6);
    trySubmit();
  });

  pinInput.addEventListener('keydown', (e) => {
    const allowed = ['Backspace','Delete','ArrowLeft','ArrowRight','Tab','Enter'];
    if (!/\d/.test(e.key) && !allowed.includes(e.key)) {
      e.preventDefault();
    }
    if (e.key === 'Enter'){
      e.preventDefault();
      trySubmit();
    }
  });

  KEYS.forEach(btn => btn.addEventListener('click', () => {
    if (pinInput.value.length >= 6) return trySubmit();
    pinInput.value = (pinInput.value + btn.dataset.key).slice(0,6);
    pinInput.dispatchEvent(new Event('input'));
    pinInput.focus();
  }));

  ACTIONS.forEach(btn => btn.addEventListener('click', () => {
    const action = btn.dataset.action;
    if (action === 'clear') pinInput.value = '';
    if (action === 'back') pinInput.value = pinInput.value.slice(0, -1);
    pinInput.dispatchEvent(new Event('input'));
    pinInput.focus();
  }));
})();
</script>
</body>
</html>
