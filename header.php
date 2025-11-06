<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$companyName = $_SESSION['company_name'] ?? null;
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    <a class="navbar-brand" href="index.php">DoItMeow</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="docs.php">Docs</a></li>
        <li class="nav-item"><a class="nav-link" href="inventory.php">Inventory</a></li>
        <li class="nav-item"><a class="nav-link" href="training.php">Training</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if ($companyName): ?>
          <span class="text-muted small d-none d-md-inline">Company: <?=htmlspecialchars($companyName)?></span>
        <?php endif; ?>
        <span id="hdrUserName" class="small text-muted d-none d-md-inline"></span>
        <span id="hdrUserAdmin" class="badge bg-secondary d-none">Admin</span>
        <button id="hdrUserBtn" class="btn btn-sm btn-outline-primary" type="button">Sign in</button>
        <button id="adminBtn" class="btn btn-sm btn-outline-dark" type="button">Admin</button>
        <a class="btn btn-sm btn-outline-danger" href="company_logout.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<!-- User PIN Modal -->
<div class="modal fade" id="userLoginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Sign in with PIN</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="input-group mb-2">
          <span class="input-group-text">PIN</span>
          <input type="password" inputmode="numeric" maxlength="6" class="form-control" id="pin_input" placeholder="••••••">
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-between">
          <div class="d-flex flex-column gap-2">
            <button class="btn btn-outline-secondary u" data-n="7" type="button">7</button>
            <button class="btn btn-outline-secondary u" data-n="4" type="button">4</button>
            <button class="btn btn-outline-secondary u" data-n="1" type="button">1</button>
            <button class="btn btn-outline-secondary u" data-n="0" type="button">0</button>
          </div>
          <div class="d-flex flex-column gap-2">
            <button class="btn btn-outline-secondary u" data-n="8" type="button">8</button>
            <button class="btn btn-outline-secondary u" data-n="5" type="button">5</button>
            <button class="btn btn-outline-secondary u" data-n="2" type="button">2</button>
            <button class="btn btn-outline-danger" id="pin_clear" type="button">Clear</button>
          </div>
          <div class="d-flex flex-column gap-2">
            <button class="btn btn-outline-secondary u" data-n="9" type="button">9</button>
            <button class="btn btn-outline-secondary u" data-n="6" type="button">6</button>
            <button class="btn btn-outline-secondary u" data-n="3" type="button">3</button>
            <button class="btn btn-success" id="pin_login" type="button">Sign In</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Admin Unlock Modal -->
<div class="modal fade" id="adminUnlockModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Admin unlock</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2 small text-muted">Enter an admin user PIN for this company.</div>
        <div class="input-group mb-2">
          <span class="input-group-text">PIN</span>
          <input type="password" inputmode="numeric" maxlength="6" class="form-control" id="admin_pin" placeholder="••••••">
        </div>
        <div class="d-flex flex-wrap gap-2 justify-content-between">
          <div class="d-flex flex-column gap-2">
            <button class="btn btn-outline-secondary a" data-n="7" type="button">7</button>
            <button class="btn btn-outline-secondary a" data-n="4" type="button">4</button>
            <button class="btn btn-outline-secondary a" data-n="1" type="button">1</button>
            <button class="btn btn-outline-secondary a" data-n="0" type="button">0</button>
          </div>
          <div class="d-flex flex-column gap-2">
            <button class="btn btn-outline-secondary a" data-n="8" type="button">8</button>
            <button class="btn btn-outline-secondary a" data-n="5" type="button">5</button>
            <button class="btn btn-outline-secondary a" data-n="2" type="button">2</button>
            <button class="btn btn-outline-danger" id="admin_clear" type="button">Clear</button>
          </div>
          <div class="d-flex flex-column gap-2">
            <button class="btn btn-outline-secondary a" data-n="9" type="button">9</button>
            <button class="btn btn-outline-secondary a" data-n="6" type="button">6</button>
            <button class="btn btn-outline-secondary a" data-n="3" type="button">3</button>
            <button class="btn btn-success" id="admin_confirm" type="button">Unlock</button>
          </div>
        </div>
        <div class="mt-2 small text-muted">Tip: entering a different admin PIN will also <strong>switch user</strong> for this session.</div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Global WHOAMI shared across pages
window.WHOAMI = window.WHOAMI || null;

(function(){
  function refreshWhoAmI(cb){
    $.getJSON('api_mt.php?action=whoami&t='+Date.now(), function(d){
      window.WHOAMI = d || null;
      const nameEl = $('#hdrUserName');
      const adminEl = $('#hdrUserAdmin');
      const btn = $('#hdrUserBtn');

      if (window.WHOAMI && window.WHOAMI.user){
        const u = window.WHOAMI.user;
        const full = ((u.first_name||'') + ' ' + (u.last_name||'')).trim();
        nameEl.text(full).removeClass('d-none');
        adminEl.toggleClass('d-none', !u.is_admin);
        btn.text('Switch user');
      } else {
        nameEl.text('').addClass('d-none');
        adminEl.addClass('d-none');
        btn.text('Sign in');
      }
      document.dispatchEvent(new CustomEvent('whoami:changed', {detail: window.WHOAMI}));
      if (cb) cb(window.WHOAMI);
    });
  }

  // User sign in / switch user button
  $('#hdrUserBtn').on('click', function(){
    if (window.WHOAMI && window.WHOAMI.user){
      // Switch user: logout inner, then open PIN
      $.post('api_mt.php?action=logoutUser', {}, function(){
        refreshWhoAmI(function(){
          $('#pin_input').val('');
          new bootstrap.Modal(document.getElementById('userLoginModal')).show();
        });
      });
    } else {
      // Sign in: open PIN modal
      $('#pin_input').val('');
      new bootstrap.Modal(document.getElementById('userLoginModal')).show();
    }
  });

  // User PIN keypad
  $(document).on('click', '.u', function(){
    const n = $(this).data('n')+'';
    const inp = $('#pin_input');
    if (inp.val().length < 6) inp.val(inp.val()+n);
  });
  $('#pin_clear').on('click', function(){ $('#pin_input').val(''); });
  $('#pin_input').on('input', function(){
    this.value = this.value.replace(/\D/g,'');
    if (this.value.length >= 6) $('#pin_login').trigger('click');
  });
  $('#pin_login').on('click', function(){
    const pin = $('#pin_input').val();
    if (!/^\d{6}$/.test(pin)) { alert('Enter 6‑digit PIN'); return; }
    $.post('api_mt.php?action=loginPin', {pin}, function(){
      const m = bootstrap.Modal.getInstance(document.getElementById('userLoginModal'));
      if (m) m.hide();
      refreshWhoAmI();
    }).fail(function(){ alert('PIN not recognized'); });
  });

  // Admin button → admin dashboard
  function goAdmin(){ window.location.href = 'admin_dashboard.php'; }
  $('#adminBtn').on('click', function(){
    refreshWhoAmI(function(w){
      if (w && w.user && w.user.is_admin){ goAdmin(); return; }
      // else prompt for admin PIN
      $('#admin_pin').val('');
      new bootstrap.Modal(document.getElementById('adminUnlockModal')).show();
    });
  });

  // Admin keypad
  $(document).on('click', '.a', function(){
    const n = $(this).data('n')+'';
    const inp = $('#admin_pin');
    if (inp.val().length < 6) inp.val(inp.val()+n);
  });
  $('#admin_clear').on('click', function(){ $('#admin_pin').val(''); });
  $('#admin_pin').on('input', function(){
    this.value = this.value.replace(/\D/g,'');
    if (this.value.length >= 6) $('#admin_confirm').trigger('click');
  });
  $('#admin_confirm').on('click', function(){
    const pin = $('#admin_pin').val();
    if (!/^\d{6}$/.test(pin)) { alert('Enter 6‑digit PIN'); return; }
    $.post('api_mt.php?action=loginPin', {pin}, function(){
      // Now logged as this user, check admin
      refreshWhoAmI(function(w){
        if (w && w.user && w.user.is_admin){
          const m = bootstrap.Modal.getInstance(document.getElementById('adminUnlockModal'));
          if (m) m.hide();
          goAdmin();
        } else {
          alert('That PIN is not an admin.');
        }
      });
    }).fail(function(){ alert('PIN not recognized'); });
  });

  // Initial and periodic refresh so pages can react
  refreshWhoAmI();
  setInterval(refreshWhoAmI, 60000);
  // If redirected here with ?admin_required=1, open admin modal immediately
  const params = new URLSearchParams(window.location.search);
  if (params.get('admin_required') === '1'){
    $('#adminBtn').trigger('click');
  }
})();
</script>

<link rel="stylesheet" href="/arrowchat/external.php?type=css">
<script src="/arrowchat/external.php?type=core"></script>
