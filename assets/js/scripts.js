$(document).ready(function(){
    function loadTasks(){
        $.getJSON('api.php?action=getTasks', function(tasks){
            let html = '';
            const grouped = {'Morning': [], 'Mid-Day': [], 'Afternoon': []};
            tasks.forEach(t => { if(grouped[t.timeframe]) grouped[t.timeframe].push(t); });
            for (const tf of ['Morning','Mid-Day','Afternoon']){
                if (grouped[tf].length){
                    html += `<h3 class='mt-4'>${tf}</h3>`;
                    grouped[tf].forEach(t => {
                        html += `<div class='col-12 col-md-6'>
                            <div class='card p-3 shadow-sm'>
                                <h5>${t.title}</h5>
                                <p class='mb-2'>${t.description || ''}</p>
                                <button class='btn btn-success completeBtn' data-id='${t.id}'>Mark Complete</button>
                            </div>
                        </div>`;
                    });
                }
            }
            $('#taskList').html(html);
        });
    }
    function loadUsers(){
        $.getJSON('api.php?action=getUsers', function(users){
            $('#userSelect').html(users.map(u => `<option value='${u.id}'>${u.name}</option>`));
        });
    }
    loadTasks();
    loadUsers();
    setInterval(loadTasks, 60000);

    $(document).on('click', '.completeBtn', function(){
        $('#taskId').val($(this).data('id'));
        new bootstrap.Modal($('#completeModal')).show();
    });
    $('#confirmComplete').click(function(){
        $.post('api.php?action=markComplete', {
            task_id: $('#taskId').val(),
            user_id: $('#userSelect').val()
        }, function(){
            bootstrap.Modal.getInstance($('#completeModal')).hide();
            loadTasks();
        });
    });
});
