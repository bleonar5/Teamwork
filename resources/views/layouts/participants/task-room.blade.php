@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/room.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="vendor/materialize.min.js"></script>
  <script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.2.1.js"></script>
  @yield('task_js')
@stop

@section('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('css/room.css') }}">
  @yield('task_css')
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



$( document ).ready(function() {

  jQuery.get( "https://teamwork-token-gen.herokuapp.com/access_token?channel=group&uid={{ $user->id }}", function( data ) {
      token = data.token;
      console.log('token now: '+token);
      
      alert( "Load was performed." );
      
      params = {
          mode:"rtc",
          codec:"h264",
          appID:"0aa76e778b3d46548fa61c6a7adaf5c7",
          channel: "group",
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
          M.AutoInit();
        });
      join(rtc,params);
    });

  jQuery.get('/cryptography-intro', function(data) {
    console.log(data);
    $('#content').html(data);
  });

});

</script>

<div class="container">
  <div class="row vertical-center">
    <div class='col-sm-9 text-center' id='content'>
      @yield('task_frame')
    </div>
    <div class="col-sm-3 text-center">
      <h4> Your Team: </h4>
      <div class="agora-theme" border='solid black 1px'>
        <div class="video-grid" id="video">
        </div>
      </div>
    </div>
  </div>
</div>


@stop
