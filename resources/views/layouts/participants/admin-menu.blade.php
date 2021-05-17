@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginatorWithWait.js') }}"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@stop

@section('content')
<script>

$( document ).ready(function() {

    
});

</script>
<div class="container" >
  <div class="row vertical-center" style='height:80vh'>
    <div class="col-md-12 text-center" style='margin-bottom: 20vh'>
      <h3>Admin Page</h4>
        <hr>
      <div class=".col-sm-4 text-center">
        <button class='btn btn-lg btn-primary' onclick='window.location.href="/admin-page"' style='margin-right:200px'>Manage Session</button>
        <button class='btn btn-lg btn-primary' onclick='window.location.href="/historical-data"'>Historical Data</button>
      </div>
    </div>
  </div>
</div>

@stop
