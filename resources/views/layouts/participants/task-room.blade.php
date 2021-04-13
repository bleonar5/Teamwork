@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/room.js') }}"></script>
  <script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.2.1.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/room.css') }}">
@stop

@section('content')
<script>
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
var resourceId = '';
var sid = '';
var time_remaining = parseInt('{{ $time_remaining }}');


$( document ).ready(function() {
  var subsession_itv = setInterval(function(){
    console.log(time_remaining);
    time_remaining -= 1;
      $('#time_remaining').text(time_remaining > 0 ? new Date(time_remaining * 1000).toISOString().substr(14, 5) : '00:00');
          
  },1000);

  if('{{ $clear }}' === '1'){
    localStorage.clear();
    var uri = window.location.toString();
    if (uri.indexOf("?") > 0) {
      var clean_uri = uri.substring(0, uri.indexOf("?"));
      window.history.replaceState({}, document.title, clean_uri);
    }
  }
  
  window.addEventListener('beforeunload',function(event){
    jQuery.ajax({
                                  type:"POST",
                                  url:"https://teamwork-agora-api-caller.herokuapp.com/stop",
                                  data: JSON.stringify({
                                    "resourceId":resourceId,
                                    "sid":sid,
                                    "cName":"group{{ $user->group_id }}"
                                  }),
                                  success: function(data){
                                    console.log(data);
                                  },
                                  contentType: "application/json; charset=UTF-8"
                                });
  });



  jQuery.get( "https://teamwork-token-gen.herokuapp.com/access_token?channel=group{{ $user->group_id }}&uid={{ $user->id }}", function( data ) {
      token = data.token;
      console.log('token now: '+token);
      
      params = {
          mode:"rtc",
          codec:"h264",
          appID:"0aa76e778b3d46548fa61c6a7adaf5c7",
          channel: "group{{ $user->group_id }}",
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
          //M.AutoInit();
        });
      join(rtc,params);
      if('{{ $user->group_role }}' === 'leader'){
        $.ajax({
          type: "POST",
          url: "https://teamwork-agora-api-caller.herokuapp.com/acquire",
          data: JSON.stringify({
          "cName":"group{{ $user->group_id }}"
          }),
          success: function(data){
            console.log(data);
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
                console.log(data);
                sid= data['sid'];

                /*itv = setInterval(function(){
                  jQuery.ajax({
                    type:"POST",
                    url:"https://agora-api-caller.herokuapp.com/query",
                    data: JSON.stringify({
                      "resourceId":data['resourceId'],
                      "cName":"${e://Field/channelName}",
                      "token":token,
                      "sid":sid
                    }),
                    success: function(data){
                      //console.log(data);
                    },
                    contentType: "application/json; charset=UTF-8"
                  });
                },10000);*/
              },
              contentType: "application/json; charset=UTF-8"
            })
          },
          contentType: "application/json; charset=UTF-8"
        });
      }
      
    });
  jQuery.get('/get-group-task', function(data){
        console.log(data);
        $('#content').html(data);
      });
  });
  /*if("{{ $task->name }}" === "Cryptography"){
    if ("{{ $task->intro_completed }}" === "1"){
      jQuery.get('/cryptography', function(data){
        console.log(data);
        $('#content').html(data);
      });
    }
    else{
      jQuery.get('/cryptography-intro', function(data) {
      console.log(data);
      $('#content').html(data);
    });
    }
  }
  if("{{ $task->name }}" === "Memory"){
    if ("{{ $task->intro_completed }}" === "1"){
      jQuery.get('/memory-group', function(data){
        console.log(data);
        $('#content').html(data);
      });
    }
    else{
      jQuery.get('/memory-group', function(data) {
      console.log(data);
      $('#content').html(data);
    });
    }
  }*/



</script>

<div class="container">
  @if($user->group_role == 'leader')
    <div>
      <h5 style='text-align:center;margin: auto;padding-top:10px;'>Task ends in: <span id='time_remaining'>{{ gmdate('i:s',$time_remaining) }}</span></h3>
    </div>
  @endif
  <div class="row vertical-center" style='min-height:95vh'>
    <div class='col-sm-8 text-center' id='content' >
      
    </div>
    <div class="col-sm-4 text-center">
      <h4> Your Team: </h4>
      <div class="agora-theme" border='solid black 1px'>
        <div class="video-grid" id="video">
        </div>
      </div>
    </div>
  </div>
</div>


@stop
