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
    $("#word-list-responses").submit();
    event.preventDefault();
  });

  $("#manual-submit").on("click", function(event) {
    deleteCookie('task_timer');
  });

  initializeTimer(120, function() {
    $("input").prop( "readonly", true );
    $('#submitPrompt').modal();
  }, 'unscramble-cookie');
});

</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="pull-right" id="timer"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="pull-right text-primary" id="timer"></div>
      <h3>Unscramble the following words:</h3>
      <div class="word-list text-center">
        @foreach($words as $word)
          <span>{{ $word }}</span>
        @endforeach
      </div>
      <form name="unscramble-words-list" id="word-list-responses" class="word-list" action="/unscramble-words" method="post">
        {{ csrf_field() }}
        @foreach($words as $key => $word)
          <div class="form-group">
            <input type="text" class="form-control" name="responses[]">
          </div>
        @endforeach
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
