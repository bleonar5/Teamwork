@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      @if(\Session::get('msg'))
        <h3>{{ \Session::get('msg') }}</h3>
      @else
        <h3>
          Please continue
        </h3>
      @endif
        <div class="text-center">
          <a class="btn btn-primary memory-nav btn-lg reporter mb-md-4"
                 href="/end-group-task" role="button"
                 >Continue</a>
        </div>
    </div>
  </div>
</div>
@stop
