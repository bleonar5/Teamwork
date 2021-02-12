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
});

</script>

<div class="container">
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
</div>
@stop
