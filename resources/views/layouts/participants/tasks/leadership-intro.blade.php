@extends('layouts.master')

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>
$( document ).ready(function() {

});

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-10 offset-md-1 text-center inst">
      <h2 class="text-primary">
        Personality Questions
      </h2>
      <h3 class="text-success">
        Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
      </h3>
      <h4>
        Next, we’ll ask a final set of questions about you. This will take about <strong>4 minutes</strong>. Remember, your answers will be kept in absolute confidence, and deleted at the conclusion of the study. 
      </h4>
      <h4>
        Please be honest. Select the option that best describes yourself.
      </h4>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/leadership">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
