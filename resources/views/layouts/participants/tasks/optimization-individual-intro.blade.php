@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/optimization.js') }}"></script>
  <script src="{{ URL::asset('js/probability-distributions.js') }}"></script>
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>
$( document ).ready(function() {
  $("#alert").hide();

  var functionName = "{{ $optFunction }}";
  var MAX_RESPONSES = {{ $maxResponses }};
  var f = taskFunctions.{{ $optFunction }};
  var guessNumber = 0;
  var responses = [];

  $("#guess-prompt").hide();

  $("#next").on('click', function(event) {
    if($(".optimization-practice").is(":visible") && guessNumber == 0) {
      $("#alert").html('Enter a number between 0 and 300 before continuing!');
      $("#alert").show();
      event.stopImmediatePropagation();
    }
  });

  $("#submit-guess").on("click", function(event) {
    $("#alert").hide();
    var n = $("#guess").val();

    if(n < 0 || n > 300 || n == '') {
      $("#warning").show();
      return;
    }

    guessNumber++;

    result = f(n).toFixed(2);
    responses.push({guess: n, result: result});
    $("#guess-history").append("<tr><td>" + guessNumber +"</td><td>" + n + "</td><td>" + result + "</td></tr>");
    $("#guess").val('');
    $("#guess-prompt").hide();
    $("#guess").focus();


    if(guessNumber == MAX_RESPONSES) {

    }

    else {

    }

    $("#practice-prompt").html('Try entering another number between 0 and 300');

    event.preventDefault();
  });

  instructionPaginator(function(){ window.location = '/optimization-individual';});
});
</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Optimization Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          The goal of this task is to try to find the number (between 0 and 300)
          that results in your computer returning the biggest possible value.
          You will have {{ $maxResponses }} guesses.
          A guess can be any number between 0 and 300.
        </h4>
        <h4>
          After you enter your guess, the computer will give you back a number.
          There is a systematic relationship between the number you guess, and
          the number you receive, but the relationship may be difficult to
          understand. Usually, numbers that are close to each other will receive
          very similar outputs.
        </h4>
        <h4>
          After your {{ $maxResponses }} guesses, you will be asked to enter the number that you
          believe gives the highest response. <strong>It is only this final
          answer - which you will enter after the prompt - that determines
          your score!</strong>
        </h4>
      </div>

      <div id="inst_2" class="inst">
        <h2 class="text-primary">Optimization Practice</h2>
        <h4>
          Let&#39;s start with a practice. Say the underlying relationship
          (which you won’t know) looks like this:
        </h4>
        <img src="/img/optimization-task/function-example.png" style="width:325px; height: auto;">
        <h4>
          In this example, you get the biggest result when you enter 240. Practice
          entering numbers, and look at the results; then hit next
        </h4>
        <!--
        <h4 class="text-warning" id="practice-prompt">
          Practice: enter a number between 0 and 300 and click "Enter Practice Guess".<br>
          Your results will appear below.
          When you&#39;re finished practicing, click "Next" button below.
        </h4>
        -->
        <div class="alert alert-danger" id="alert" role="alert"></div>
        <div class="row text-center">
          <div class="col-md-6 offset-md-3 justify-content-center">
            <form class="form-inline optimization-practice" name="optimization">
              <div class="form-group">
                <input type="number" class="form-control" id="guess" min="0" max="300">
              </div>
              <div class="form-group">
                <button class="btn btn-primary" id="submit-guess" type="submit">Enter Practice Guess</button>
              </div>
            </form>
            <table class="table table-bordered table-sm optimization-practice" id="guess-history">
              <tr class="text-center">
                <th>Guess #</th>
                <th>Your guess</th>
                <th>Result</th>
              </tr>
            </table>
            </div>
          </div>
      </div>
      <div id="inst_3" class="inst">
        <h4>
          Now, you will do the optimization task {{ count($totalTasks) }}
          separate times. Each time, there will be a different underlying
          relationship. Each time, you will have {{ $maxResponses }} guesses
          to try to find a number that gives you a big value in return.
        </h4>
        <h4>
          This task isn't timed, but most people take less than ten minutes in total.
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
