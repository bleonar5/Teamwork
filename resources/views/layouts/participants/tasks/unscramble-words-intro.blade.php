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
      <h3>
        This is a group task.
      </h3>
      <h3>
        On the next page is a list of 24 words with their letters scrambled. Your task is to
        work as a group to unscramble as many of them as you can in two minutes.
      </h3>
      <h3>
        Each item on the list has only one correct answer. Your group will receive
        one point for each correct answer.
      </h3>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/unscramble-words">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
