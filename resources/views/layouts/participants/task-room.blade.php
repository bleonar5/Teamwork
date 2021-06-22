@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/room.js?v=001') }}"></script>
  <script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.2.1.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/room.css') }}">
@stop

@section('content')
<script>
//VARIABLES NEEDED FOR VIDEO CONFERENCING AND CLOUD STORAGE
var resourceId = '';
var sid = '';

var rtc = {
  client: null,
  joined: false,
  published: false,
  localStream: null,
  remoteStreams: [],
  params: {}
};

var resolutions = [
  {
    name: "default",
    value: "default",
  },
  {
    name: "480p",
    value: "480p",
  },
  {
    name: "720p",
    value: "720p",
  },
  {
    name: "1080p",
    value: "1080p"
  }
];

//GET TIME TIL SUBSESSION ENDS, FOR LEADER
var task_time_remaining = parseInt('{{ $time_remaining }}');


$( document ).ready(function() {
  //SET TIMER FOR LEADER
  var subsession_itv = setInterval(function(){

    task_time_remaining -= 1;

    $('#task_time_remaining').text(task_time_remaining > 0 ? new Date(task_time_remaining * 1000).toISOString().substr(14, 5) : '00:00');
          
  },1000);

  //ENDS CLOUD RECORDING WHEN LEADER LEAVES PAGE
  //PROBABLY UNECESSARY OR AT LEAST SHOULD BE SET TO HAPPEN AT SUBSESSION END
  /*
  window.addEventListener('beforeunload',function(event){
    jQuery.ajax({
      type:"POST",
      url:"https://teamwork-agora-api-caller.herokuapp.com/stop",
      data: JSON.stringify({
        "resourceId":resourceId,
        "sid":sid,
        "cName":"group{{ $user->group_id }}"
      }),
      contentType: "application/json; charset=UTF-8"
    });
  });
  */

  //PINGS CUSTOM TOKEN-GENERATOR HEROKU APP FOR ACCESS TO WEB CONFERENCE,
  //APP COMMUNICATES WITH AGORA API
  jQuery.get( "https://teamwork-token-gen.herokuapp.com/access_token?channel=group{{ $user->group_id }}&uid={{ $user->id }}", function( data ) {
    //TOKEN REQUIRED FOR ACCESS
    token = data.token;
      
    //PARAMETERS. SHOULD MOVE APPID TO CONFIG PROBABLY
    params = {
      mode:"rtc",
      codec:"h264",
      appID:"0aa76e778b3d46548fa61c6a7adaf5c7",
      //CHANNEL NAME IS WHAT LINKS USERS INTO THE SAME VIDEO CALL
      channel: "group{{ $user->group_id }}",
      //UID MUST BE AN INTEGER, CURRENTLY SET TO UNIQUE USER_ID
      uid:"{{ $user->id }}",
      token:token,
    };
    
    // This will fetch all the devices and will populate the UI for every device. (Audio and Video)
    getDevices(function (devices) {
      if(devices.audios.length >= 1)
        params.microphoneId = devices.audios[0].value;
      if(devices.videos.length >= 1)
        params.cameraId =  devices.videos[0].value;
      // To populate UI with different camera resolutions
      resolutions.forEach(function (resolution) {
        params.cameraResolution = "default";
      });
    });

    //ATTEMPTS TO JOIN VIDEO CALL
    join(rtc,params);

    //IF USER IS LEADER, SEND AJAX CALL TO CUSTOM API ROUTER APP TO START CLOUD RECORDING
    //OUTER AJAX CALLS TO CHECK IF RECORDING AVAILABLE, INNER AJAX STARTS RECORDING
    if('{{ $user->group_role }}' === 'leader'){

      $.ajax({
        type: "POST",
        url: "https://teamwork-agora-api-caller.herokuapp.com/acquire",
        data: JSON.stringify({
          //SAME AS 'CHANNEL'
          "cName":"group{{ $user->group_id }}"
        }),
        success: function(data){
          //STARTS RECORDING
          resourceId = data['resourceId'];

          jQuery.ajax({
            type:"POST",
            url:"https://teamwork-agora-api-caller.herokuapp.com/start",
            data: JSON.stringify({
              "resourceId":data['resourceId'],
              "cName":"group{{ $user->group_id }}",
              "token":token
            }),
            success: function(data){
              sid= data['sid'];
            },
            contentType: "application/json; charset=UTF-8"
          });

        },
        contentType: "application/json; charset=UTF-8"
      });

    }
      
  });

  //RENDERS THE TASK AT HAND IN THE 'CONTENT' CONTAINER, WITH WEB CONFERENCE OVERLA
  jQuery.get('/get-group-task', function(data){
    $('#content').html(data);
  });
});



</script>

<div class="container">
  @if($user->group_role == 'leader')
    <div>
      <h5 style='text-align:center;margin: auto;padding-top:10px;'>Task ends in: <span id='task_time_remaining'>{{ gmdate('i:s',$time_remaining) }}</span></h3>
    </div>
  @endif
  <div class="row vertical-center" style='min-height:95vh'>
    <div class='col-sm-8 text-center' id='content' >
      
    </div>
    <div class="col-sm-4 text-center">
      <h4> Your Team: </h4>
      <hr/>
      <h5> Your role: 
          @if($user->group_role == 'follower1')
              Equations
          @elseif($user->group_role == 'follower2')
              Hypotheses
          @else
              Leader
          @endif
      </h5>
      <div class="agora-theme" border='solid black 1px'>
        <div class="video-grid" id="video">
        </div>
      </div>
    </div>
  </div>
</div>


@stop
