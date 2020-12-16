@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginatorWithWait.js') }}"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@stop

@section('content')
<script>
$( document ).ready(function() {
  localStorage.clear();
  window.location.href = '/participant-login';
});

</script>
<div class="container" >
</div>


@stop
