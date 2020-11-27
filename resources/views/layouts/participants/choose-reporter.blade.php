@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginatorWithWait.js') }}"></script>
@stop

@section('content')
<script>
$( document ).ready(function() {

  var userId = {{ \Auth::user()->id }};
  var groupId = {{ \Auth::user()->group_id }};
  var taskId = {{ $taskId }};
  var token = "{{ csrf_token() }}";
  var modal = "#waiting-for-group";

  var instructionPaginator = new InstructionPaginator(1, [1], userId, groupId, taskId, token, modal, function(){ return ;});

  $("#next").on('click', function(event) {
    $("#instr_nav").hide();
    instructionPaginator.nav('next');
    if($("#inst_2").is(":visible")) {
      $("#instr_nav").hide();
      event.stopImmediatePropagation();
    }
  });
});
</script>
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
        <div id="inst_1" class="inst">
          <h2>Entering your Group’s Answers</h2>
          <h4>Your group will only submit one answer for each problem.</h4>
          <h4>
            <strong>At this point, choose one member of your group to be "The Reporter"</strong>.
            This person will be responsible for entering the group’s answers.
          </h4>
          <h4>Take a moment to discuss who will be The Reporter.</h4>
          <h4 class="text-danger">Please make sure that the Reporter is sitting in the middle 
          of the three seats.</h4>
        </div>
        <div id="inst_2" class="inst">
          <div class="text-center">
            <a class="btn btn-success memory-nav btn-lg reporter mb-md-4"
                   href="/choose-reporter/true" role="button"
                   >I am the Reporter</a><br>
            <a class="btn btn-warning memory-nav btn-lg not-reporter"
                  href="/choose-reporter/false" role="button"
                  >I am NOT the Reporter</a>
          </div>
        </div>
        <div id="instr_nav" class="text-center">
          <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;"><br />
        </div>
    </div>
  </div>
</div>

@include('layouts.includes.waiting-for-group')

@stop
