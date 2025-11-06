// === Minimal JS add-on for Undo ===
// Requires jQuery and your existing index.js logic.
// Call loadCompleted() on page load and after marking a task complete.
(function(){
  function escapeHtml(s){ return (s||'').replace(/[&<>"']/g, m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;' }[m])); }
  window.loadCompleted = function(){
    $.getJSON('api.php?action=recentCompletions', function(rows){
      const list = $('#completedList');
      if (!list.length) return;
      list.empty();
      if (!rows.length){
        list.append('<div class="list-group-item text-muted">No completions yet today.</div>');
        return;
      }
      rows.forEach(r => {
        const item = $('<div class="list-group-item d-flex justify-content-between align-items-center"></div>');
        const left = $('<div></div>');
        left.append('<div class="fw-semibold">'+escapeHtml(r.title || '(deleted task)')+'</div>');
        left.append('<div class="small text-muted">'+escapeHtml(r.completed_by || 'Unknown')+' • '+escapeHtml(r.timeframe || '')+' • '+escapeHtml(r.completed_at)+'</div>');
        const btn = $('<button class="btn btn-sm btn-outline-danger">Undo</button>');
        btn.on('click', function(){
          $.post('api.php?action=uncompleteTask', {task_id: r.task_id}, function(){
            if (typeof loadTasks === 'function') loadTasks(); // refresh your existing list
            loadCompleted();
          });
        });
        item.append(left).append(btn);
        list.append(item);
      });
    });
  };

  $(document).on('click', '#refreshCompleted', function(){ loadCompleted(); });

  // Try to hook into your existing "mark complete" success if it emits an event
  // Otherwise, make sure to call loadCompleted() after your AJAX success that marks complete.
  $(function(){ loadCompleted(); setInterval(loadCompleted, 60000); });
})();