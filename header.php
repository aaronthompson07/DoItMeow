<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
function is_admin() { return !empty($_SESSION['is_admin']); }
?>
<title>DoItMeow</title>
<nav class='navbar navbar-expand-lg navbar-dark bg-dark mb-4'>
  <div class='container-fluid'>
    <a class='navbar-brand' href='index.php'>DoItMeow</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class='collapse navbar-collapse' id='nav'>
      <ul class='navbar-nav me-auto'>
        <li class='nav-item'><a class='nav-link' href='index.php'>Home</a></li>
        <li class='nav-item'><a class='nav-link' href='docs.php'>Documentation</a></li>
        <li class='nav-item'><a class='nav-link' href='inventory.php'>Inventory</a></li>
        <li class='nav-item'><a class='nav-link' href='reports.php'>Reports</a></li>
        <li class='nav-item'><a class='nav-link' href='training.php'>Training</a></li>
        <?php if (is_admin()): ?>
          <li class='nav-item'><a class='nav-link' href='admin_dashboard.php'>Admin</a></li>
          <li class='nav-item'><a class='nav-link' href='admin_logout.php'>Logout</a></li>
        <?php else: ?>
          <li class='nav-item'><a class='nav-link' href='admin_login.php'>Admin</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
