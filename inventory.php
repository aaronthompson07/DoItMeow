<?php include 'db.php'; if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
<!doctype html><html><head><meta charset="utf-8"><title>Inventory</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>.session-controls .btn{margin-right:.25rem}</style>
</head>
<body class="bg-light"><?php include 'header.php'; ?>
<div class="container">
  <div class="row g-3 align-items-end">
    <div class="col-md-4">
      <label class="form-label">Location</label>
      <select id="locationSelect" class="form-select"></select>
    </div>
    <div class="col-md-8">
      <label class="form-label d-block">Current Session</label>
      <div class="d-flex align-items-center">
        <span id="sessionBadge" class="badge bg-secondary me-3">Checking...</span>
        <div class="session-controls">
          <!-- Visible to ALL users now -->
          <button id="startSession" class="btn btn-success btn-sm">Start New Session</button>
          <button id="closeSession" class="btn btn-warning btn-sm">Close Current Session</button>
        </div>
      </div>
      <small class="text-muted d-block mt-1">Counts auto-save as you type. Blank fields keep previous values when the session is closed.</small>
    </div>
  </div>

  <div class="mt-4">
    <table class="table table-striped align-middle">
      <thead><tr><th>Item</th><th>Previous Count</th><th>New Count (autosave)</th></tr></thead>
      <tbody id="invRows"></tbody>
    </table>
  </div>
</div>

<script>
let currentSession=null;

function loadLocations(){
  $.getJSON('api_inventory.php?action=getLocations', function(l){
    const sel=$('#locationSelect').empty();
    l.forEach(x=> sel.append(new Option(x.name,x.id)));
    if(l.length){ sel.val(l[0].id).trigger('change'); }
  });
}

function loadSession(cb){
  $.getJSON('api_inventory.php?action=getCurrentSession', function(s){
    currentSession=s;
    $('#sessionBadge').text(s?('Open #'+s.id+' — '+s.started_at):'No open session');
    if (typeof cb === 'function') cb();
  });
}

function loadTable(){
  const locId=$('#locationSelect').val(); if(!locId) return;
  $.getJSON('api_inventory.php?action=getLatestCountsByLocation&location_id='+locId, function(rows){
    const tb=$('#invRows').empty();
    rows.forEach(r=>{
      const tr=$('<tr/>');
      tr.append($('<td/>').text(r.item_name));
      tr.append($('<td/>').text(r.quantity));
      const input=$('<input type="number" min="0" class="form-control form-control-sm" />')
        .attr('data-item', r.item_id)
        .attr('placeholder', r.quantity);
      if(!currentSession){ input.prop('disabled',true).attr('placeholder','Start a session to count'); }
      input.on('input', function(){
        if(!currentSession) return;
        const qty=$(this).val();
        if(qty==='') return;
        $.post('api_inventory.php?action=updateCount', {
          session_id: currentSession.id,
          item_id: r.item_id,
          quantity: qty
        });
      });
      tr.append($('<td/>').append(input));
      tb.append(tr);
    });
  });
}

$(function(){
  loadSession(loadTable);
  loadLocations();
  $('#locationSelect').on('change', loadTable);

  // Buttons for everyone now
  $('#startSession').on('click', function(){
    $.post('api_inventory.php?action=startSession', {}, function(resp){
      loadSession(loadTable);
    }, 'json');
  });

  $('#closeSession').on('click', function(){
    if(!currentSession){ alert('No open session to close.'); return; }
    if(!confirm('Close the current inventory session? Items left blank will retain their previous values.')) return;
    $.post('api_inventory.php?action=closeSession', { session_id: currentSession.id }, function(resp){
      loadSession(loadTable);
    }, 'json');
  });

  setInterval(function(){ loadSession(loadTable); }, 60000);
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
