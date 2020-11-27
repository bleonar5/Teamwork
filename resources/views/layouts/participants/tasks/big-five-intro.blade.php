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
        Personality Test
      </h2>
      <h3 class="text-success">
        Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
      </h3>
      <h4>
        We’ll start with some basic questions about personality.
      </h4>
      <h4>
        This will take about 10 minutes. Remember, your answers will be kept
        in absolute confidence, and deleted at the conclusion of the study.
      </h4>
      <h4>
        Describe yourself as you generally are now, not as you
        wish to be in the future. Describe yourself as honestly as you can,
        compared to other people you know of roughly the same age and sex.
      </h4>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/big-five">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
