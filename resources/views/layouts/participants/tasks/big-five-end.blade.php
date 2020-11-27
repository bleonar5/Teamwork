@extends('layouts.master')

@section('content')

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h3>
        You have now completed this task.<br>
        Press Continue to move on to the next task.
      </h3>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/end-individual-task">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
