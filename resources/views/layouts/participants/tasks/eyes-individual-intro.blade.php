@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('content')

<script>
  $(document).ready(function() {
    instructionPaginator(function(){window.location = '/rmet-individual';});
  });
</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <div id="inst_1" class="inst">
        <h2 class="text-primary">
          Reading the Mind in the Eyes Task
        </h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          In this task, you’ll be presented with pictures of people's eyes.
          Your job is to draw conclusions about what the person is thinking or
          feeling.
        </h4>
        <h4>
          This task isn't timed; it should not take more than 10 minutes.
        </h4>
        <h4>
          The next page has more detailed instructions.
        </h4>
      </div>
      <div id="inst_2" class="inst">
        <h2 class="text-primary text-center">
          Reading the Mind in the Eyes Task
        </h2>
        <h4>
          For each set of eyes, select the word that best describes what
          the person in the picture is thinking or feeling. You may feel that more
          than one word is applicable but please choose just the word
          which you consider to be most suitable.
        </h4>
        <h4>
          Before making your choice, make sure that you have read all 4 words. 
          If you don't know what a word means you can click on it.
          Your computer will open a new page that defines the word. After reading
          the definition, return to this page and continue the task.
        </h4>
        <h4>
          When you hit Next, the task will begin.
        </h4>
      </div>
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;"><br />
      </div>
    </div>
  </div>
</div>
@stop
