@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('content')

<script>

    $( document ).ready(function() {
      instructionPaginator(function(){ window.location = '/end-individual-task';});
    });

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Memory Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          Last are some tests of memory.<br>
          We will test three different types of memory.
        </h4>
        <div class="row">
          <div class="col-md-3 offset-md-4">
            <h4>
              <ul>
                <li>Image memory</li>
                <li>Word memory</li>
                <li>Story memory</li>
              </ul>
            </h4>
          </div>
        </div>
        <h4>
          The tests are short. In total, all the memory tests will take around 10 minutes.
        </h4>
        <h4 class="text-danger">
          <strong>Please do NOT write anything down during these tasks.</strong>
        </h4>
      </div>

      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;"><br />
      </div>
    </div>
  </div>
</div>
@stop
