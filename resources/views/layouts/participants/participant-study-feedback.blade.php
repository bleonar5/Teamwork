@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h4 class="mb-lg-4">
        You have completed all the tasks. Thank you for participating!
      </h4>
      <form name="feedback-form" action="/study-feedback" method="post">
        {{ csrf_field() }}
        <div class="form-group">
          <label for="feedback">
            <h4>
              If you have any feedback about the tasks, or experienced any difficulties,
              please note them here.
            </h4>
          </label>
          <textarea class="form-control" name="feedback" rows="3"></textarea>
        </div>
        <div class="text-center">
          <h4>
            @if($hasCode)
              Click continue to get a verification code for payment purposes.
            @else
              Click below to continue
            @endif
          </h4>
          <button class="btn btn-lg btn-primary" type="submit">Continue</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop
