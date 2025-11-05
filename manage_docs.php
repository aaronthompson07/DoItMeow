<?php require_once 'auth.php'; require_admin(); include 'db.php'; ?>
<!doctype html><html><head><meta charset="utf-8"><title>Manage Docs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.tiny.cloud/1/jbne733ivwo4iows4en4vkm11xrl71o8qq8s8307ahvo670n/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
</head><body class="bg-light"><?php include 'header.php'; ?>
<div class='container'>
  <div class='row g-4'>
    <div class='col-md-5'><div class='card p-3'>
      <h5>Categories</h5>
      <div class='input-group mb-2'>
        <input id='catName' class='form-control' placeholder='New category'>
        <button id='addCat' class='btn btn-primary'>Add</button>
      </div>
      <ul id='catList' class='list-group'></ul>
      <small class='text-muted mt-2 d-block'>Tip: Click a category name to rename it.</small>
    </div></div>
    <div class='col-md-7'><div class='card p-3'>
      <h5>Docs</h5>
      <div class='row g-2 mb-2'>
        <div class='col-6'><input id='docTitle' class='form-control' placeholder='New doc title'></div>
        <div class='col-6'><select id='docCat' class='form-select'></select></div>
      </div>
      <button id='addDoc' class='btn btn-primary mb-3'>Add Doc</button>

      <div class='row g-2 mb-2'>
        <div class='col-8'><select id='moveDocSelect' class='form-select'></select></div>
        <div class='col-4'><select id='moveDocTarget' class='form-select'></select></div>
      </div>
      <button id='moveDocBtn' class='btn btn-secondary mb-3'>Move Doc</button>

      <ul id='docList' class='list-group'></ul>

      <hr><h5>Edit Content</h5>
      <select id='editDocSelect' class='form-select mb-2'></select>
      <textarea id='editor'></textarea>
      <div class='mt-2 d-flex gap-2'>
        <button id='saveContent' class='btn btn-success'>Save</button>
        <span id='saveMsg' class='text-success' style='display:none'>Saved ✔</span>
      </div>
    </div></div>
  </div>
</div>
<script>
let editorReady=false;

function loadCats(){
  $.getJSON('api_docs.php?action=listCategories', function(c){
    const cl=$('#catList').empty();
    const sel=$('#docCat').empty();
    const target=$('#moveDocTarget').empty();
    c.forEach(x=>{
      cl.append(`<li class='list-group-item d-flex justify-content-between align-items-center'>
        <span contenteditable data-id='${x.id}' class='catName'>${x.name}</span>
        <button class='btn btn-sm btn-danger delCat' data-id='${x.id}'>Delete</button>
      </li>`);
      sel.append(new Option(x.name,x.id));
      target.append(new Option(x.name,x.id));
    });
  });
}

function loadDocs(selectedId=null){
  $.getJSON('api_docs.php?action=listDocsByCategory', function(ds){
    const dl=$('#docList').empty();
    const moveSel=$('#moveDocSelect').empty();
    const editSel=$('#editDocSelect').empty();
    ds.forEach(d=>{
      dl.append(`<li class='list-group-item d-flex justify-content-between align-items-center'>
        <span>#${d.id} — ${d.title} <em class='text-muted'>(${d.category_name})</em></span>
        <button class='btn btn-sm btn-danger delDoc' data-id='${d.id}'>Delete</button>
      </li>`);
      moveSel.append(new Option('#'+d.id+' '+d.title,d.id));
      editSel.append(new Option('#'+d.id+' '+d.title,d.id));
    });
    if (selectedId){
      $('#editDocSelect').val(selectedId).trigger('change');
      $('#moveDocSelect').val(selectedId);
    } else if (ds.length){
      $('#editDocSelect').val(ds[0].id).trigger('change');
    }
  });
}

// --- Events ---
$(document).on('click','#addCat', ()=>{
  const name=$('#catName').val().trim(); if(!name) return;
  $.post('api_docs.php?action=addCategory',{name}, ()=>{ $('#catName').val(''); loadCats(); });
});

$(document).on('blur','.catName', function(){
  const id=$(this).data('id'); const name=$(this).text().trim();
  if(!name) return;
  $.post('api_docs.php?action=renameCategory',{id,name});
});

$(document).on('click','.delCat', function(){
  $.post('api_docs.php?action=deleteCategory',{id:$(this).data('id')}, resp=>{
    if(!resp.ok && resp.error) alert(resp.error);
    loadCats(); loadDocs();
  }, 'json');
});

$('#addDoc').on('click', ()=>{
  const title=$('#docTitle').val().trim(); const category_id=$('#docCat').val();
  if(!title || !category_id) return;
  $.post('api_docs.php?action=addDoc',{title,category_id}, function(resp){
    $('#docTitle').val('');
    loadDocs(resp.id); // select the newly created doc in editor
  }, 'json');
});

$(document).on('click','.delDoc', function(){
  $.post('api_docs.php?action=deleteDoc',{id:$(this).data('id')}, ()=> loadDocs());
});

$('#moveDocBtn').on('click', ()=>{
  const id=$('#moveDocSelect').val(); const category_id=$('#moveDocTarget').val();
  if(!id || !category_id) return;
  $.post('api_docs.php?action=moveDoc',{id,category_id}, ()=> loadDocs(id));
});

// Load content into TinyMCE when editor selection changes
$('#editDocSelect').on('change', function(){
  const id=$(this).val();
  if(!id) return;
  $.getJSON('api_docs.php?action=getDoc&id='+id, function(d){
    if (editorReady && tinymce.get('editor')) {
      tinymce.get('editor').setContent(d.content || '');
    } else {
      $('#editor').val(d.content || '');
    }
  });
});

// Save content
$('#saveContent').on('click', function(){
  const id=$('#editDocSelect').val();
  const content = editorReady && tinymce.get('editor') ? tinymce.get('editor').getContent() : $('#editor').val();
  $.post('api_docs.php?action=saveDoc',{id,content}, function(){
    $('#saveMsg').stop(true,true).fadeIn(150).delay(1000).fadeOut(400);
  });
});

// Init
tinymce.init({
  selector:'#editor',
  height: 360,
  plugins: 'link lists image table code',
  toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code',
  setup: (ed)=>{
    ed.on('init', ()=>{ editorReady=true; $('#editDocSelect').trigger('change'); });
  }
});

$(function(){ loadCats(); loadDocs(); });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
