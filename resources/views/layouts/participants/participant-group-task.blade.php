@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <h3>
        This is a group task.
      </h3>
      <h3>
        Decide which member of your group will be responsible for inputting answers.
      </h3>
      <h3>
        That person should click the 'Group Sign In' button in the lower left.
      </h3>
      <h3>
        The other members of the group can click the 'Continue' button once
        the group's answers have been submitted.
      </h3>
      <div>
        <a class="btn btn-lg btn-primary pull-left"
           role="button"
           href="/group-login">Group Sign In
        </a>
        <a class="btn btn-lg btn-primary pull-right"
           role="button"
           href="/get-individual-task">Continue
        </a>
      </div>
    </div>
  </div>
</div>
@stop
