@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginatorWithWait.js') }}"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@stop

@section('content')
<script>
$( document ).ready(function() {

  var userId = {{ \Auth::user()->id }};
  var groupId = {{ \Auth::user()->group_id }};
  var token = "{{ csrf_token() }}";
  var modal = "#waiting-for-group";

  window.addEventListener('beforeunload',function(event){
    console.log('yes');
    $.post("/leave-room", {
            _token: "{{ csrf_token() }}"
          } );
  });

  Pusher.logToConsole = true;

    var pusher = new Pusher('7475be7ce897a0019304', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('player-joined-room', function(data) {
      //alert(JSON.stringify(data));
      if(!$('#'+data['user']['id']).length)
        $('#waitingList').append("<li style='text-align:left' id='"+data['user']['id'].toString()+"'>"+data['user']['id']+" : "+data['user']['group_role']+"</li>");
    });
    channel.bind('player-left-room', function(data) {
      //alert(JSON.stringify(data));
      if($('#'+data['user']['id']).length)
        $('#'+data['user']['id']).remove();
    });
});

</script>
<div class="container" >
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <div class=".col-sm-4 text-center">
        <ul id='waitingList'>
          @foreach ($users as $key => $user)
            <li style="text-align:left" id="{{ $user->id }}">{{ $user->id}} : {{ $user->group_role }}</li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
</div>

@include('layouts.includes.waiting-for-group')

@stop
