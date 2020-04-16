<?php

require_once "init.php";

include_once $templates . "header.inc.php";

?>
<div class="colmun colmun-1 colmun-left">
  <div class="image_svg">
    <img class="svg_todo" width="500" src="<?php echo $db->getUrl('img', false) . 'undraw_project_completed_w0oq.svg' ?>"/>
  </div>
</div>

<div class="colmun colmun-1 colmun-right">
  <div class="todo_card">
    <div class="todo_header">
      <h1 class="todo_title"> ToDo List </h1>
    </div>
    <div class="todo_body">
      <form  id="addForm" method="post">
        <div class="add-control">
           <div class="form-group has-feedback">
              <input id="todo_title" name="todo_title" type="text" class="form-control" placeholder="✍️ Add item..."/>
              <button id="addButton" type="submit" name="submit" ><i class="fa fa-plus form-control-feedback add-btn" title="Add item"></i></button>
           </div>
           <ul class="todo-list">
           </ul>
        </div>
      </form>
      <br>

    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#addForm').submit(function(e) {
        e.preventDefault();
        var title = $('#todo_title').val();
        $.ajax({
            type: "POST",
            url: 'todo.php',
            data: {action:'add',task_name:title},
            success: function(response)
            {
              var obj = JSON.parse(response);
              if (obj.result.status > 0) {
                var task_id = obj.data[0].task_id;
                var task_name = obj.data[0].task_name;

                htmlcode = '<li><input type="checkbox" id="box-' + task_id + '" ><label for="box-' + task_id + '">' + task_name + '</label><span class="delete"><i class="fas fa-trash"></i></span></li>';
                if ($('.todo-list').find('li').length >  0) {
                  $('.todo-list').append(htmlcode);

                } else if ($('.todo-list').find('li').length == 0) {
                  $('.todo-list').html(htmlcode);
                }
                $('#todo_title').val('');
              }
          }
       });
    });
    $.ajax({
        type: "POST",
        url: 'todo.php',
        data: {action:'view'},
        success: function(response){
          var htmlcode = '',
          checked = "",
          status = "";
          var obj = JSON.parse(response);
          if (obj.result.status > 0) {
            if (obj.data.length > 0) {
              for (var i = 0; i < obj.data.length; i++) {
                var task_id = obj.data[i].task_id;
                var task_name = obj.data[i].task_name;
                var task_status = obj.data[i].task_status;

                if (task_status == 1 ) {
                  checked = "checked";
                  status = "class='done'";
                }
                htmlcode = '<li ' + status + '><input type="checkbox" id="box-' + task_id + '" ' + checked + '><label for="box-' + task_id+ '">' + task_name + ' </label><span class="delete fas fa-trash"></span></li>';
                $('.todo-list').append(htmlcode);
                $('input[type="checkbox"]').click(function(){
                  var id = $(this).attr('id');
                  id = id.replace("box-", "");
                  if ($(this).attr('checked') == 'checked') {
                    $($(this)).removeAttr('checked');
                    $($(this)).parent().removeClass('done').addClass('not_done');
                    var action = 'not_done';
                    var  task_status = '0';
                  } else {
                    $($(this)).attr('checked','checked');
                    $($(this)).parent().addClass('done');
                    var action = 'done';
                    var  task_status = '1';
                  }
                  $.ajax({
                      type: "POST",
                      url: 'todo.php',
                      data: {action:action, task_id:id, task_status:task_status},
                      success: function(response)
                      {
                        console.log(response);
                      }
                 });
                });
                $('.delete').click(function(){
                  var parent = $(this).parent();
                  $.ajax({
                      type: "POST",
                      url: 'todo.php',
                      data: {action:'delete', task_id:task_id},
                      success: function(response) {
                        var obj = JSON.parse(response);
                        if (obj.result.status > 0) {
                          parent.addClass('done_remove');
                          setTimeout(function(){ parent.remove(); }, 1000);
                        }
                      }
                    });
                  });
              }
          } else {
           htmlcode = '<span>There is No tasks</span>';
           $('.todo-list').html(htmlcode);
         }
       }
     }
   });
 });
//});
</script>
<?php
include_once $templates . "footer.inc.php";
?>
