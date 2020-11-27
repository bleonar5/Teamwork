$(document).ready(function(){

  var taskArray = [];

  $(".task-params").hide();

  $("#task").on('change', function(){
    $(".task-params").hide();
    var task = $(this).val();
    $("#" + task).show();
  });

  $(document).on('click', "#task-list .selected-task .delete", function(){
    $(this).parent().parent().remove();
    taskArray.splice($(this).attr('id'), 1);
    $("#taskArray").val(JSON.stringify(taskArray));
  });

  $("#addTask").on('click', function(){
    var task = $("#task option:selected").val();
    var params = {};

    $("#" + task + " .params select").each(function(sel){
      params[$(this).data('name')] = $(this).val();

    });


    var count = taskArray.push({
      taskName: task,
      taskParams: params
    });

    var key = count - 1;

    $("#task-list").append('<div class="selected-task alert alert-secondary"><h6>' + task + '<div class="delete ml-md-4 float-right" id="'+ key +'"><i class="fas fa-minus-circle"></i></div></h6><p>' + JSON.stringify(params) + '</p></div>');

    $("#taskArray").val(JSON.stringify(taskArray));
    $("#task option:eq(0)").prop('selected', true);
    $(".task-params").hide();
  });

});
