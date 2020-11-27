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

});

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h2 class="text-primary">Optimization Task {{ count($completedTasks) + 1 }} of {{ count($totalTasks) }}</h2>
      <h4>
        Now you'll perform the task again. The relationship between the numbers
        you enter and the ones you receive will be different this time. Otherwise,
        the task is the same. Try to find a number between 0 and 300 that gives
        you the biggest possible output.
      </h4>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/optimization-individual">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
