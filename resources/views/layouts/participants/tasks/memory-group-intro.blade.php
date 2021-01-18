@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/memoryPaginator.js') }}"></script>
@stop

@section('content')

<script>
var user_id = {{ $user->id }};
$( document ).ready(function() {

  Pusher.logToConsole = true;

    var pusher = new Pusher('{{ config("app.PUSHER_APP_KEY") }}', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('task-channel');

    channel.bind('clear-storage', function(data){
      console.log('freedom!');
      localStorage.clear();
      window.location.href='/participant-login/memory-group';
    });
    channel.bind('all-ready', function(data) {
      $('#next').attr('disabled',false);
      $("#inst_" + page_count).hide();
      page_count += 1;
      localStorage.setItem('page_count',page_count);
      //alert(JSON.stringify(data));
          if(page_count > $(".inst").length){
            console.log('longer');

            $("#pagination-display").hide();
            $('.instr_nav').hide();
            //$("#waiting").show();

            channel.unbind('all-ready');
            //ocalStorage.setItem('pageCount',1);

            $.get('/end-group-task', function(data) {
              localStorage.clear();
              $('#content').html(data);
            });
          }

          // Show the new instruction
          $("#inst_"+page_count).show();

          // Hide back button if we're at the start
          if(page_count <= 1){
            $("#instr_nav #back").hide();
          }

          else {
            $("#instr_nav #back").show();
          }

          // If there is a page # display, update it
          if($("#pagination-display").length) {

            $("#curr-page").html(page_count);
          }

          $('#next').val('Next');

          event.preventDefault();

    });
  instructionPaginator(function(){window.location = '/end-group-task';});
  });
</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Welcome to the final set of group tasks!</h2>
        <h4>
          You will be working together for 10-12 minutes, trying to solve the COLLECTIVE MEMORY task.
        </h4>
        <h4>
          Please take a moment to introduce yourselves.
        </h4>
      </div>
      <div id="inst_2" class="inst">
        <h4>
          This task is similar to the memory tasks you completed as an individual.
        </h4>
        <h4>
          Now, you will be working on the task <b>as a group</b>
        </h4>
      </div>
      <div id="inst_3" class="inst">
        <h4>
          The collective Memory task examines three types of memory: <span style='color:green'>images</span>, <span style='color:#e0ac13'>words</span>, and <span style='color:red'>stories</span>
        </h4>
        <h4>
          Your group must memorize all three types of stimuli <b>at the same time</b>
        </h4>
        <h4>
          <b>Please do NOT write anything down during this task</b>
        </h4>
        <h4>We'll start with a practice round</h4>
      </div>
        {{ csrf_field() }}
        <div id="instr_nav" class="text-center">
          <button class="btn btn-primary instr_nav btn-lg" type="submit" name="next" id="next">Next &#8680;</button>
        </div>
    </div>
  </div>
</div>
@stop
