<?php

require_once "init.php";

include_once $templates . "header.inc.php";

?>
<div class="colmun colmun-1 colmun-left">
  <div class="image_svg">
    <img class="svg_todo" width="500"
      src="<?php echo $db->getUrl('img', false) . 'undraw_project_completed_w0oq.svg' ?>" />
  </div>
</div>

<div class="colmun colmun-1 colmun-right">
  <div class="todo_card">
    <div class="todo_header">
      <h1 class="todo_title"> ToDo List </h1>
    </div>
    <div class="todo_body">
      <form id="addForm" method="post">
        <input name="token" type="text" hidden value="<?php echo $token  ?>" />

        <div class="add-control">
          <div class="form-group has-feedback">

            <input id="todo_title" name="todo_title" type="text" class="form-control" placeholder="✍️ Add item..." />
            <button id="addButton" type="submit" name="submit"><i class="fa fa-plus form-control-feedback add-btn"
                title="Add item"></i></button>
          </div>
          <div class='error'>
            <div class="alert alert-danger" role="alert">
              <strong></strong>
            </div>
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
  $(document).ready(function () {
    $('.error').hide();
    $('#addForm').submit(function (e) {
      e.preventDefault();
      var title = $('#todo_title').val(); // Task name
      var ss = $('input[name=token]').val(); // Token Value
      if (title == '') {
        $('.error').show();
        $('.error .alert > strong').html('You Must add Task Name');
      } else {
        $.ajax({
          type: "POST",
          url: 'todo.php', //Action Url
          data: {
            action: 'add',  // Action Type
            task_name: title, // Task Name
            token: ss // Token
          },
          success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            if (obj.result.status > 0) {
              var task_id = obj.data[0].task_id;
              var task_name = obj.data[0].task_name;
              htmlcode = '<li><input type="checkbox" id="box-' + task_id + '" ><label for="box-' +
                task_id + '">' + task_name +
                '</label><span class="delete"><i class="fas fa-trash"></i></span></li>';
              if ($('.todo-list').find('li').length > 0) {
                $('.todo-list').append(htmlcode);
              } else if ($('.todo-list').find('li').length == 0) {
                $('.todo-list').html(htmlcode);
              }
              $('#todo_title').val('');
            }
          }
        });
      }
    });
    $.ajax({
      type: "POST",
      url: 'todo.php', //Action Url
      data: {
        action: 'view', // Action Type
        token: ss // Token 
      },
      success: function (response) {
        var htmlcode = '',
            checked = "",
            status = "",
            obj = JSON.parse(response);
        if (obj.result.status > 0) {
          if (obj.data.length > 0) {
            for (var i = 0; i < obj.data.length; i++) {
              var task_id = obj.data[i].task_id,
              var task_name = obj.data[i].task_name,
              var task_status = obj.data[i].task_status;
              if (task_status == 1) {
                checked = "checked='checked'";
                status = "class='done'";
              } else {
                status = "class='not_done'";
                checked = "";
              }
              htmlcode = '<li ' + status + '><input type="checkbox" id="box-' + task_id + '" ' + checked +
                '><label for="box-' + task_id + '">' + task_name +
                ' </label><span class="delete fas fa-trash"></span></li>';
              $('.todo-list').append(htmlcode);
              var todoList = $('.todo-list');
              todoList.find('li').each(function () {
                $(this).find('input[type=checkbox]').click(function () {
                  var id = $(this).attr('id');
                  id = id.replace("box-", "");
                  var action, task_status;
                  var ss = $('input[name=token]').val(), // Token Value
                      checked = $(this).is(':checked');
                  if (checked == true) {
                    $(this).removeAttr('checked');
                    $(this).parent().removeClass('not_done').addClass('done');
                    action = 'done';
                    task_status = '1';
                  } else if (checked == false) {
                    $(this).attr("checked", "checked");
                    $(this).parent().removeClass('done').addClass('not_done');
                    action = 'not_done';
                    task_status = '0';
                  }
                  $.ajax({
                    type: "POST",
                    url: 'todo.php', //Action Url
                    data: {
                      action: action, // Action Type
                      task_id: id, // Task Id 
                      task_status: task_status, // Task Status
                      token: ss // Token
                    },
                    success: function (response) {
                      console.log(response);
                    }
                  });
                });


              });
              $('.delete').click(function () {
                var parent = $(this).parent(), // Find Parent Element
                    ss = $('input[name=token]').val(); // Token Value
                $.ajax({
                  type: "POST",
                  url: 'todo.php', //Action Url
                  data: {
                    action: 'delete', // Action Type
                    task_id: task_id, // Task Id 
                    token: ss // Token
                  },
                  success: function (response) {
                    var obj = JSON.parse(response); // Parse Json
                    if (obj.result.status > 0) {
                      parent.addClass('done_remove');
                      setTimeout(function () {
                        parent.remove();
                      }, 1000);
                      if ($('.todo-list').find('li').length == 0) {
                        htmlcode = '<span>There is No tasks</span>';
                        $('.todo-list').html(htmlcode);
                      }
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
</script>
<?php
include_once $templates . "footer.inc.php";
?>