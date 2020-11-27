@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.2.1.js"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/room.css') }}">
@stop

@section('content')
<script>

$( document ).ready(function() {

  

});

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      
    </div>
  </div>
</div>


@stop
