@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/optimization.js') }}"></script>

@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>

$( document ).ready(function() {

  var f = taskFunctions.a;
  console.log(f(3));
});

</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
    </div>
  </div>
</div>

@stop
