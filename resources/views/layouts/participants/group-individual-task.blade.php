@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h3>
        This is an individual task.
      </h3>
      <h3>
        Click the "Continue" button below to sign in with your
        participant number and enter your individual responses.
      </h3>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/participant-login">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
