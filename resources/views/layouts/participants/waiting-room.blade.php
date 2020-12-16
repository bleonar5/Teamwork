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
$( document ).ready(function() {
  
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

    var pusher = new Pusher('{{ env("PUSHER_APP_KEY") }}', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('player-joined-room', function(data) {
      //alert(JSON.stringify(data));
        roomTotal += 1;
        $('#roomTotal').text(roomTotal.toString());
        //if(roomTotal == 3){
          //window.location.href = '/task-room';
        //} 
        


        //$('#waitingList').append("<li style='text-align:left' id='"+data['user']['id'].toString()+"'>"+data['user']['id']+" : "+data['user']['group_role']+"</li>");
    });
    channel.bind('player-left-room', function(data) {
      roomTotal -= 1;
      $('#roomTotal').text(roomTotal.toString());
      //alert(JSON.stringify(data));
      //if($('#'+data['user']['id']).length)
        //$('#'+data['user']['id']).remove();
    });
    $.get('/in-room', {},function(data){
      console.log('in room');
      roomTotal += 1;
      $('#roomTotal').text(roomTotal.toString());
    });
    channel.bind('send-to-task', function(data) {
        window.location.href= '/task-room';
    });
});

</script>
<div class="container" >
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h4> You have entered the Skills Lab waiting room</h4>
      <div class=".col-sm-4 text-center">
        <h3>There are <span id='roomTotal'>{{ count($users) }}</span> participants in the room</h3>
      </div>
    </div>
  </div>
</div>

@include('layouts.includes.waiting-for-group')

@stop
