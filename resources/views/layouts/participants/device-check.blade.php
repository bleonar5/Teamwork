@extends('layouts.master')

@section('js')
	<script src="{{ URL::asset('js/room-test.js') }}"></script>
  <script src="https://cdn.agora.io/sdk/release/AgoraRTCSDK-3.2.1.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')

<script>
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
  $('#ins-text').hide();
	jQuery.get( "https://teamwork-token-gen.herokuapp.com/access_token?channel=user{{ $user->id }}&uid={{ $user->id }}", function( data ) {
      token = data.token;
      console.log('token now: '+token);
      
      params = {
          mode:"rtc",
          codec:"h264",
          appID:"0aa76e778b3d46548fa61c6a7adaf5c7",
          channel: "user{{ $user->id }}",
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
    });
    $('#continue-button').on('click',function(event){
    	if($(event.target).text().includes('Proceed'))
    		window.location.href = '/end-group-task';
    	//else
    		//$('#stop-warning').modal('toggle');
    });
});

</script>
<div class="container" >
	<div class='row'>
		<div class='col-sm-9' style='margin:auto;margin:auto;'>
				<h5 style='text-align:center;margin:auto;'>
					Checking the status of your audio and video devices.</h5>
				
		</div>
	</div><br/>
	<div class='row'>
		<div class='col-sm-6' style='margin:auto'>
			<h4 style='text-align:center;margin:auto'>
				Audio Status: <span id='audio-connected' >Connecting...</span>
			</h4>

		</div>
	</div>
	<div class='row'>
		<div class='col-sm-6' style='margin:auto'>
			<h4 style='text-align:center;margin:auto'>
				Video Status: <span id='video-connected'>Connecting...</span>
			</h4>

		</div>
	</div><br/>
	<div class='row'>
		<div class='col-sm-6' style='margin:auto; text-align:center'>
			<button class='btn btn-primary' style='margin:auto;' id='continue-button' disabled>Waiting for connection...</button>

		</div>
	</div></div><br>
  <div  id='ins-text' class='row'>
    <ul style='list-style-type: none;border:1px black solid;padding:0;width:90%;margin:auto'><li>
        <h5 style='text-align:center;margin:auto;'>
          You must have an available microphone and camera to proceed.</h5>
        </li><br/>
        <li>
          <h5 style='text-align:center;margin:auto;'>
            1. Close any other apps or webpages open that use your microphone/camera (e.g. Zoom, Skype, Omegle). Then reload this page.
          </h5>
        </li><br/>
        <li>
          <h5 style='text-align:center;margin:auto;'>2. If a prompt appears on this page asking your permission to access your microphone/camera, <b>select "allow"</b>. 
          </h5>
        </li><br/>
        <li>
          <h5 style='text-align:center;margin:auto;'>3. Look for a camera/microphone <span style='color:red'>icon</span> next to the address bar in your browser. Click this icon and select "allow" or "always allow". Then reload this page.
          </h5>
        </li><br/>
        <li>
          <img src='img/mic-icon.png' />
        </li>
        
      </ul>
  </div>
</div>

  <div class="modal fade" id="stop-warning">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id='alert-text-equation' class="modal-title text-center">
          You need a working microphone and camera in order to proceed. Follow the instructions on the page to ensure that you're allowing access to the devices. If all else fails, try a different web browser or restart your computer. If you still can't connect, either return the study on Prolific or contact the study administrator.
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" id="ok-time-up" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->



@stop
