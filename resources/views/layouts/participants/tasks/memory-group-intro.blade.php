@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('content')

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Memory Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        @if($introType == 'group_1_intro')
        <h4>
          Next is a test of your group’s collective memory. This will be similar
          to the individual memory tasks you completed earlier. We will ask you
          to remember images, words and stories.
        </h4>
        <h4>
          The group task has a twist. Rather than asking you to memorize images
          first, then words, then stories, <strong>we’ll ask you to memorize all
            three types of stimuli at the same time</strong>.
        </h4>
        <h4>
          We’ll start with a practice round.
        </h4>
        <h4>
          The practice will begin when all three members of your group have clicked "next".
        </h4>
        @endif
      </div>
      <form action="/memory-group" method="post">
        {{ csrf_field() }}
        <div id="instr_nav" class="text-center">
          <button class="btn btn-primary instr_nav btn-lg" type="submit" name="next" id="next">Next &#8680;</button>
        </div>
      </form>
    </div>
  </div>
</div>
@stop
