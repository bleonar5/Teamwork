@extends('layouts.master')

@section('js')
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
@stop

@section('content')
<script>

//BASIC SHUFFLE ALGORITHM
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

var room_type; //CRYPTO (1) OR MEMORY (2)
var time_remaining = parseInt('{{ $time_remaining }}'); //TIME TIL NEXT SUBSESSION
var userId = {{ \Auth::user()->id }};
var groupId = {{ \Auth::user()->group_id }};
var token = "{{ csrf_token() }}"; //FOR AJAX REQUESTS

$( document ).ready(function() {
  //START TIMER
  var subsession_itv = setInterval(function(){

    time_remaining -= 1;

    $('#time_remaining').text(time_remaining > 0 ? new Date(time_remaining * 1000).toISOString().substr(14, 5) : '00:00');
          
  },1000);

  //RESET LOCALSTORAGE SO IT DOESN'T AFFECT FUTURE TASKS
  localStorage.clear();

  room_type='{{ $task }}'; 

  //INITIATE PUSHER FOR REAL-TIME EVENT MONITORING
  Pusher.logToConsole = true;

  var pusher = new Pusher('{{ $PUSHER_APP_KEY }}', {
    cluster: 'us2'
  });

  var channel = pusher.subscribe('my-channel');

  //KICK USER OUT IF STUDY CLOSES
  channel.bind('study-closed', function(data) {

    window.location.href = '/study-closed';

  });

  //SEND USER TO TASK WHEN SUBSESSION BEGINS
  channel.bind('send-to-task', function(data) {

    if(userId === data['user']['id'])
      window.location.href='/task-room';
    
  });

  //TRIGGERS POP-UP NOTIFYING USER THEY ARE A WAITER THIS ROUND
  channel.bind('alert-waiter', function(data) {

    if(userId === data['user']['id']){
      alert('You were not selected for a group this round, but PLEASE STAY around for the next session in ~10 minutes in order to continue and complete the study. You will definitely be matched to a group in the next round. Please use the green chat button if you have any questions/concerns.');
      window.location.reload();
    }
    
  });

  //PING SERVER TO CONFIRM THAT USER IS STILL ON PAGE
  var itv = setInterval(function() {
    console.log('GOING OFF');
    $.get('/still-here', {
      _token: "{{ csrf_token() }}"
    });
  },3000);
    
});

</script>
<div class="container" >
  @if($time_remaining)
    <div>
      <h5 style='text-align:center;margin: auto;padding-top:10px;'>Next group starts in: <span id='time_remaining'>{{ gmdate('i:s',$time_remaining) }}</span></h3>
    </div>
  @endif
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h4> You have entered the Skills Lab waiting room</h4>
      <div class=".col-sm-4 text-center">
        <h3>You will be matched with your teammates and sent to the group task shortly</h3>
      </div>
      <div class=".col-sm-4 text-center">
        <h3 style="color:green">Reminder: if you're having difficulties, click the green button to talk to a researcher.</h3>
      </div>
    </div>
  </div>
</div>

@include('layouts.includes.waiting-for-group')

@stop
