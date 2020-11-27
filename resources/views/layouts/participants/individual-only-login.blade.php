@extends('layouts.master')


@section('content')
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
      <form action="/individual-login" method="post">
        <h4>Please enter your email address below:</h4>
        <h6>This should be the same address you use to register for studies</h6>
        {{ csrf_field() }}
        <fieldset class="bg-light p-4 rounded">
          <div class="form-group">
            <label for="participant_id">Email</label>
            <input type="text" class="form-control" name="participant_id"
                   value="{{ old('participant_id') }}">
            @if(isset($package))
              <input type="hidden" name="task_package" value="{{ $package }}">
            @endif
            @if(isset($surveyCode))
              <input type="hidden" name="survey_code" value="{{ $surveyCode }}">
            @endif
          </div>
          <div class="text-center">
            <button class="btn btn-lg btn-primary" type="submit">Sign In</button>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
@stop
