@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      @foreach($introContent as $content)
        @if($content['type'] == 'header')
          <h2 class="text-primary">{!! $content['content'] !!}</h2>
        @elseif($content['type'] == 'paragraph')
          <h4>{!! $content['content'] !!}</h4>
        @endif
      @endforeach
    </div>
  </div>
</div>
@stop
