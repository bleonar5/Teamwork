@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>
$( document ).ready(function() {

  $("#timer-submit").on("click", function(event) {
    $("#brainstorming-responses").submit();
    event.preventDefault();
  });

  $("#manual-submit").on("click", function(event) {
    deleteCookie('task_timer');
  });

  initializeTimer(120, function() {
    $("input").prop( "readonly", true );
    $('#submitPrompt').modal();
  }, 'brainstorming-cookie');
});

</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="pull-right text-primary" id="timer"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="pull-right" id="timer"></div>
      <h3>Based on the following prompt, enter as many ideas as possible in the boxes below.</h3>
      <h2 class="text-primary">{{ $prompt }}</h2>
      <form name="brainstorming-responses" class="brainstorming-responses" id="brainstorming-responses" action="/brainstorming-individual" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="prompt" value="{{ $prompt }}">
        @for($i = 0; $i < 24; $i++)
          <div class="form-group">
            <input type="text" class="form-control" name="responses[]">
          </div>
        @endfor
        <div class="text-center">
          <button class="btn btn-lg btn-primary" id="manual-submit" type="submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="submitPrompt">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Your time is up.<br>Please submit your responses now</h4>
      </div>
      <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary" id="timer-submit" type="button">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
