<?php include 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Documentation</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script></head>
<body class="bg-light"><?php include 'header.php'; ?>
<div class='container-fluid p-3'><div class='row'>
  <div class='col-md-3'><div class="accordion" id="docAccordion"></div></div>
  <div class='col-md-9'><div id='docContent' class='bg-white p-3 rounded shadow-sm'>Select a document from the left.</div></div>
</div></div>
<script>
function buildSidebar(){
  $.getJSON('api_docs.php?action=listCategories', function(cats){
    $.getJSON('api_docs.php?action=listDocsByCategory', function(docs){
      const acc=$('#docAccordion').empty();
      cats.forEach(c=>{
        const collapseId='col'+c.id;
        const card=$(`<div class="accordion-item">
          <h2 class="accordion-header" id="h${c.id}">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#${collapseId}" aria-expanded="false">${c.name}</button>
          </h2>
          <div id="${collapseId}" class="accordion-collapse collapse" data-bs-parent="#docAccordion">
            <div class="accordion-body p-0"><div class="list-group" id="list${c.id}"></div></div>
          </div>
        </div>`);
        acc.append(card);
        const list=card.find('#list'+c.id);
        docs.filter(d=> d.category_id == c.id).forEach(d=>{
          const a=$(`<a href="#" class="list-group-item list-group-item-action" data-id="${d.id}">${d.title}</a>`);
          a.on('click', e=>{ e.preventDefault(); loadDoc(d.id); });
          list.append(a);
        });
      });
    });
  });
}
function loadDoc(id){
  $.getJSON('api_docs.php?action=getDoc&id='+id, function(d){
    const html = (d && d.content && d.content.trim().length) ? d.content : '<em class="text-muted">No content saved yet.</em>';
    $('#docContent').html(html);
  });
}
$(function(){ buildSidebar(); });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
