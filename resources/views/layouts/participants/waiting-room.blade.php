@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginatorWithWait.js') }}"></script>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@stop

@section('content')
<script>
function shuffle(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
}

var room_type;

$( document ).ready(function() {
  localStorage.clear();
  room_type='{{ $task }}'; 
  
  console.log($('#roomTotal').text());
  roomTotal = parseInt($('#roomTotal').text());

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
  console.log('{{ $PUSHER_APP_KEY }}');
  console.log('tourd');

    var pusher = new Pusher('{{ $PUSHER_APP_KEY }}', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('player-joined-room', function(data) {
      //alert(JSON.stringify(data));
        if(room_type === data['user']['in_room'].toString()){
          roomTotal += 1;
          $('#roomTotal').text((roomTotal < 0 ? 0 : roomTotal).toString());
        }
        
        


        //$('#waitingList').append("<li style='text-align:left' id='"+data['user']['id'].toString()+"'>"+data['user']['id']+" : "+data['user']['group_role']+"</li>");
    });
    channel.bind('player-left-room', function(data) {
      if(room_type === data['user']['in_room'].toString()){
        roomTotal -= 1;
        $('#roomTotal').text((roomTotal < 0 ? 0 : roomTotal).toString());
      }
      //alert(JSON.stringify(data));
      //if($('#'+data['user']['id']).length)
        //$('#'+data['user']['id']).remove();
    });
    channel.bind('send-to-task', function(data) {
      console.log(data);
        if(userId === data['user']['id']){
          $.post("/leave-room", {
            _token: "{{ csrf_token() }}"
          } );
          window.location.href='/task-room';
        }
    });

    var itv = setInterval(function() {
      console.log('GOING OFF');
      $.post('/still-here', {
        _token: "{{ csrf_token() }}"
      });
    },10000);

    
});

</script>
<div class="container" >
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h4> You have entered the Skills Lab waiting room</h4>
      <div class=".col-sm-4 text-center">
        <h3>There are <span id='roomTotal'>{{ count($users) }}</span> participants in the room.</h3>
      </div>
    </div>
  </div>
</div>

@include('layouts.includes.waiting-for-group')

@stop
