<?php require_once 'auth.php'; require_admin(); include 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Tasks Admin</title>
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'></head>
<body class='bg-light'><?php include 'header.php'; ?>
<div class='container'>
<div class='row'>
<div class='col-md-6'>
<h4>Add Task</h4>
<form method='post' action='save_task.php'>
<div class='mb-2'><input class='form-control' name='title' placeholder='Title'></div>
<div class='mb-2'><textarea class='form-control' name='description' placeholder='Description'></textarea></div>
<div class='mb-2'><select name='timeframe' class='form-select'><option>Morning</option><option>Mid-Day</option><option>Afternoon</option></select></div>
<div class='mb-2'><select name='type' class='form-select'><option value='single'>Single</option><option value='daily'>Daily</option><option value='weekly'>Weekly</option><option value='monthly'>Monthly</option></select></div>
<div class='mb-2'><input type='date' name='start_date' class='form-control'></div>
<div class='mb-2'><input type='date' name='end_date' class='form-control' placeholder='(optional)'></div>
<button class='btn btn-primary'>Add Task</button>
</form>
</div>
<div class='col-md-6'>
<h4>Add User</h4>
<form method='post' action='save_user.php'>
<div class='mb-2'><input class='form-control' name='name' placeholder='User Name'></div>
<button class='btn btn-primary'>Add User</button>
</form>
</div></div>
<hr>
<h4>Existing Tasks</h4>
<table class='table table-striped'><tr><th>Title</th><th>Type</th><th>Timeframe</th><th>Start</th><th>End</th></tr>
<?php $tasks=$conn->query("SELECT * FROM tasks ORDER BY start_date DESC, id DESC"); while($t=$tasks->fetch_assoc()) echo "<tr><td>{$t['title']}</td><td>{$t['type']}</td><td>{$t['timeframe']}</td><td>{$t['start_date']}</td><td>".($t['end_date']??'')."</td></tr>"; ?>
</table>
<h4>Users</h4><table class='table table-striped'><tr><th>Name</th></tr>
<?php $users=$conn->query("SELECT * FROM users ORDER BY name"); while($u=$users->fetch_assoc()) echo "<tr><td>{$u['name']}</td></tr>"; ?>
</table>
</div>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'></script></body></html>
