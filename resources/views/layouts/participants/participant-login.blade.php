
@extends('layouts.login-master')


@section('content')

<script>
$( document ).ready(function() {
  $("#sign-in").on("click", function(event) {
    event.preventDefault();
    if ($('#participant_id').val() != ""){
      $("#sign-in").attr("disabled", true);
      $("#sign-in-form").submit();
    }

  });
  if($("#notify").width() > 0) {
        console.log('no AB')
    } else {
        alert('We have detected that you are using adblocker. Please disable adblocker on our site, or the study may not work properly');
    }
  Pusher.logToConsole = true;
  var pusher = new Pusher('{{ config('app.PUSHER_APP_KEY') }}', {
      cluster: 'us2'
    });
  var channel = pusher.subscribe('my-channel');
  channel.bind('study-opened', function(data) {
      window.location.reload(true); 
    });
  channel.bind('study-closed', function(data) {
      window.location.reload(true); 
    });
});

</script>

<div class="container">
  <div id="notify">
      <div class="ads" style='width:1px'>
      </div>
  </div>
  @if($in_session)
    <div class="row justify-content-center vertical-center">
      <div class="col-md-6 p-4">
        @if($errors)
          @foreach ($errors->all() as $error)
          <h5 class="text-white bg-danger p-2">
            {{ $error }}
          </h5>
          @endforeach
        @endif
        <form action="/participant-login" id="sign-in-form" method="post">
          {{ csrf_field() }}
          <fieldset class="bg-light p-4 rounded">
            <div class="form-group">
              <label for="participant_id">PROLIFIC ID</label>
              <input type="text" class="form-control" id='participant_id' name="participant_id"
                     value="">
            </div>
            @if(isset($package))
              <input type="hidden" name="task_package" value="{{ $package }}">
            @endif
            <div class="text-center">
              <button class="btn btn-lg btn-primary" type="submit" id="sign-in">Sign In</button>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  @else
    <div class="row justify-content-center vertical-center">
      <div class="col-md-10 p-4">
        <h3 style='text-align:center'>The study is <strong>not currently open.</strong></h3>
        <h3 style='text-align:center'>This study will open at 
        @if(isset($date))
          {{ str_replace('T',' @ ',$date) }}
        @else
          some future date 
        @endif
        and will close shortly after it opens. If this date has passed, you may have missed the most recent session. Please wait for the next session to become available.</h3>
        <h3 style='text-align:center'>When it is time for the study to begin, make sure to refresh the page to check if it has opened.</h3>
        <h3 style='text-align:center'>Note: study times are listed in <strong>US Eastern Time.</strong></h3>
      </div>
    </div>
  @endif
</div>
@stop
