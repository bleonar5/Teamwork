@extends('layouts.master')

@section('content')

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h2 class="text-primary">
        The Team Role Test
      </h2>
      <h3 class="text-success">
        Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
      </h3>
      <h3>
        The purpose of this test is to better understand how you work in a team
        context. You will be asked to make judgments about the best ways to act
        in a team facing a specific situation. Some answers are better than
        others!
      </h3>
      <h3>
        You will be presented with 2 different scenarios where you face a
        work-related challenge.
      </h3>
      <h3>
        The task isn't timed, but the whole task should take a total of less
        than 10 minutes.
      </h3>
      <h3>
        Click "Next" to read the first scenario.
      </h3>
      <div class="text-center">
        <a class="btn btn-lg btn-primary"
           role="button"
           href="/team-role">Next
        </a>
      </div>
    </div>
  </div>
</div>
@stop
