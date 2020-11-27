@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/optimization.js') }}"></script>
  <script src="{{ URL::asset('js/probability-distributions.js') }}"></script>
  <script src="{{ URL::asset('js/instructionPaginatorWithWait.js') }}"></script>
  <script src="{{ URL::asset('js/timer.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>
$( document ).ready(function() {
  $("#alert").hide();


  var functionName = "{{ $function }}";
  var MAX_RESPONSES = {{ floor($maxResponses / $groupSize) }};
  var f = taskFunctions.<?= $function ?>;
  var guessNumber = 0;
  var responses = [];
  var userId = {{ \Auth::user()->id }};
  var groupId = {{ \Auth::user()->group_id }};
  var taskId = {{ $taskId }};
  var isReporter = @if($isReporter) true @else false @endif;
  var token = "{{ csrf_token() }}";

  var practiceRoundPage = 5;

  var waitingPages = [1, 2, 3, 4, 5, 6];
  var modal = "#waiting-for-group";
  var warningTimeout;
  var callback = function(){ window.location = '/optimization-group';}
  var instructionPaginator = new InstructionPaginator(1, waitingPages, userId, groupId, taskId, token, modal, callback);

  $("#guess-prompt").hide();
  $("#final-answer-error").hide();

  $("#next").on('click', function(event) {

    if($(".optimization-practice").is(":visible") && guessNumber == 0) {
      $("#alert").html('Enter a number between 0 and 300 before continuing!');
      $("#alert").show();
      event.stopImmediatePropagation();
      return;
    }
    instructionPaginator.nav('next');
    if(instructionPaginator.getCurrentPage() + 1 == practiceRoundPage) {
      warningTimeout = setTimeout(function() {
        $('#warningModal').modal();
      }, 180000);

      initializeTimer(240, function() {

        clearTimeout(warningTimeout);
        if(isReporter) $("#reporter-final-answer").modal({show: true, backdrop: 'static', keyboard: false});
        else {
          instructionPaginator.nav('next');
          $("#instr_nav").show();
        }
      });

      $("#guess").focus();
      $("#instr_nav").hide();
    }
  });

  $("#back").on('click', function(event) {

    instructionPaginator.nav('back');
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
      clearTimeout(warningTimeout);
      stopTimer();
      $('#final-guess-prompt').modal({show: true, backdrop: 'static', keyboard: false});
      $("#guess").prop('disabled', true);
      $("#submit-guess").prop('disabled', true);
    }

    event.preventDefault();
  });

  $("#final-guess-prompt-submit").on("click", function(event) {

    $('#final-guess-prompt').modal('hide');
    if(isReporter) $("#reporter-final-answer").modal({show: true, backdrop: 'static', keyboard: false});
    else {
      instructionPaginator.nav('next');
      $("#instr_nav").show();
    }
  });

  $("#final-guess-submit").on("click", function(event) {
    $("#reporter-final-answer").modal('hide');
    instructionPaginator.nav('next');
    $("#instr_nav").show();
  });


});
</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      @if($intro == 'group_1')
        <div id="inst_1" class="inst">
          <h4>Once all the members of your group are ready, click "Next"</h4>
        </div>

        <div id="inst_2" class="inst">
          <h2 class="text-primary">Optimization Task</h2>
          <h3 class="text-success">
            Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
          </h3>
          <h4>
            @if($intro == 'group_1')
              Welcome to the first group task. It is very similar to the
              Optimization Task you completed as individuals. The main difference
              is that you will now be working in a group.
            @else
              You have seen this Optimization task before.
            @endif
          </h4>
          <h4>
            This is a group task where the goal is to try to find the
            number (between 0 and 300) that results in your computer returning
            the biggest possible value.
          </h4>
          <h4>
            When all three group members have hit Next, the instructions will continue.
          </h4>
        </div>

        <div id="inst_3" class="inst">
          <h2 class="text-primary">Optimization Task</h2>
          <h4>
            Every time you do the Optimization Task, the computer has a
            different "relationship" in mind. When you enter a guess, your
            computer uses this relationship to determine the number it gives you
            in return. Below is an example relationship:
          </h4>
          <img src="/img/optimization-task/function-example.png" style="width:400px; height: auto;">
          <h4>
            As you can see, guesses around 240 result in particularly high values.
          </h4>
          <h4>
            When all three members click next, we will have a
            <strong>practice round</strong>.
          </h4>
        </div>
        <div id="inst_4" class="inst">
          <h2>Practice Round</h2>
          <h4>
            Each of you will have {{ floor($maxResponses / $groupSize) }} guesses in this practice round. In total,
            the group has {{ $maxResponses }} guesses.
          </h4>
          <h4>
            After each of you has had {{ floor($maxResponses / $groupSize) }} guesses, the computer will ask The Reporter
            to input the Group’s Best Guess.
          </h4>
          <h4>
            This practice round will not count towards your group’s score.
          </h4>
          <h4 class="text-danger">
            You are NOT allowed to use pen and paper.
          </h4>
          <h4>
            The practice round is <strong>timed</strong>. You have a total of four minutes.<br>
            We will give you a warning when you have 1 minute left.
          </h4>
        </div>
        <div id="inst_5" class="inst">
          <div class="row">
            <div class="col-md-12 text-center">
              <h3><div class="float-right text-primary" id="timer"></div></h3>
            </div>
          </div>
          <h2>Practice Round</h2>
          <h4 class="text-warning" id="practice-prompt">
            Enter your guess (between 0 and 300) below. You will have {{ floor($maxResponses / $groupSize) }}
            individual guesses.
          </h4>
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
              <div class="text-center">
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
              </div>
            </div>
          </div>
        </div>
        <div id="inst_6" class="inst">
          <h4>
            Now for the actual task. Your group will do the Optimization Task
            {{ count($totalTasks )}} separate times. Each time, there will be a
            different relationship. Each time, your group will have {{ $maxResponses }}
            guesses ({{ floor($maxResponses / $groupSize) }} guesses each) to try to find a number
            that gives you a big value in return.
          </h4>
          <h4>
            You will have four minutes for each set
            of guesses. There is a timer in the top right of the screen, and we will
            give you a warning when you have one minute remaining.
          </h4>
          <h4>
            If you need to clarify the rules of the Optimization task you can
            click Review Practice Instructions at any time.
          </h4>
          <h4>
            <strong>Spend a few moments discussing with your group how you will
              tackle the Optimization Task, then click "Next" <span class="text-danger">to begin the task</span>.</strong>
          </h4>
          <div class="text-center mb-lg-4">
            <button type="button" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
          </div>
        </div>

        @elseif($intro == 'group_2' || $intro == 'group_4')
        <div id="inst_1" class="inst">
          <h2 class="text-primary">Optimization Task</h2>
          <h3 class="text-success">
            Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
          </h3>
          <h4>
            This is the same as the task you’ve completed in previous groups.
          </h4>
          <h4>
            <strong>As you’ve done the task several times before, we won’t have
              a practice round this time.</strong>
          </h4>
          <h4>
            When all three group members have hit Next, the instructions will continue.
          </h4>
        </div>
        <div id="inst_2" class="inst">
          <h4>
            As a reminder, each of you will have {{ floor($maxResponses / $groupSize) }}
            guesses. In total, the group has {{ $maxResponses }} guesses.
          </h4>
          <h4>
            If you need to clarify the rules of the Optimization task you can
            click Review Practice Instructions.
          </h4>
          <h4>
            <strong>Spend a few moments discussing with your group how you will
              tackle the Optimization Task.</strong>
          </h4>
          <h4>
            Remember, there is no practice this time. When all three members hit
            next, <span class="text-danger">the task will begin!</span>
          </h4>
          <div class="float-left mt-lg-4">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
          </div>
        </div>

        @elseif($intro == 'group_3')
          <div id="inst_1" class="inst">
            <h4>Once all the members of your group are ready, click "Next"</h4>
          </div>

          <div id="inst_2" class="inst">
            <h2 class="text-primary">Optimization Task</h2>
            <h3 class="text-success">
              Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
            </h3>
            <h4>
              @if($intro == 'group_1')
                Welcome to the first group task. It is very similar to the
                Optimization Task you completed as individuals. The main difference
                is that you will now be working in a group.
              @else
                You've seen this Optimization task before.
              @endif
            </h4>
            <h4>
              This is a group task where the goal is to try to find the
              number (between 0 and 300) that results in your computer returning
              the biggest possible value.
            </h4>
            <h4>
              When all three group members have hit Next, the instructions will continue.
            </h4>
          </div>

          <div id="inst_3" class="inst">
            <h2 class="text-primary">Optimization Task</h2>
            <h4>
              Every time you do the Optimization Task, the computer has a
              different "relationship" in mind. When you enter a guess, your
              computer uses this relationship to determine the number it gives you
              in return. Below is an example relationship:
            </h4>
            <img src="/img/optimization-task/function-example.png" style="width:400px; height: auto;">
            <h4>
              As you can see, guesses around 240 result in particularly high values.
            </h4>
            <h4>
              When all three members click next, we will have a
              <strong>practice round</strong>.
            </h4>
          </div>
          <div id="inst_4" class="inst">
            <h2>Practice Round</h2>
            <h4>
              Each of you will have {{ floor($maxResponses / $groupSize) }} guesses in this practice round. In total,
              the group has {{ $maxResponses }} guesses.
            </h4>
            <h4>
              After each of you has had {{ floor($maxResponses / $groupSize) }} guesses, the computer will ask The Reporter
              to input the Group’s Best Guess.
            </h4>
            <h4>
              This practice round will not count towards your group’s score.
            </h4>
            <h4 class="text-danger">
              You are NOT allowed to use pen and paper.
            </h4>
            <h4>
              The practice round is <strong>timed</strong>. You have a total of four minutes.<br>
              We will give you a warning when you have 1 minute left.
            </h4>
          </div>
          <div id="inst_5" class="inst">
            <div class="row">
              <div class="col-md-12 text-center">
                <h3><div class="float-right text-primary" id="timer"></div></h3>
              </div>
            </div>
            <h2>Practice Round</h2>
            <h4 class="text-warning" id="practice-prompt">
              Enter your guess (between 0 and 300) below. You will have {{ floor($maxResponses / $groupSize) }}
              individual guesses.
            </h4>
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
                <div class="text-center">
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
                </div>
              </div>
            </div>
          </div>
          <div id="inst_6" class="inst">
            <h4>
              Now for the actual task. Your group will do the Optimization Task
              {{ count($totalTasks )}} separate times. Each time, there will be a
              different relationship. Each time, your group will have {{ $maxResponses }}
              guesses ({{ floor($maxResponses / $groupSize) }} guesses each) to try to find a number
              that gives you a big value in return.
            </h4>
            <h4>
              You will have four minutes for each set
              of guesses. There is a timer in the top right of the screen, and we will
              give you a warning when you have one minute remaining.
            </h4>
            <h4>
              If you need to clarify the rules of the Optimization task you can
              click Review Practice Instructions at any time.
            </h4>
            <h4>
              <strong>Spend a few moments discussing with your group how you will
                tackle the Optimization Task, then click "Next" <span class="text-danger">to begin the task</span>.</strong>
            </h4>
            <div class="text-center mb-lg-4">
              <button type="button" class="btn btn-lg btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
            </div>
          </div>


      @elseif($intro == 'group_4')
      <div id="inst_2" class="inst">
        <h2 class="text-primary">Optimization Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          Welcome to your last group! We’ll start with the Optimization Task.
          This is the same as the task you’ve completed in previous groups.
        </h4>
        <h4>
          Recall that the goal of this task is to try to find the number
          (between 0 and 300) that results in your computer returning the
          biggest possible value. You do this by guessing numbers, and seeing
          what the computer gives you in return.
        </h4>
        <h4>
          <strong>As you’ve done the task several times before, we won’t have
            a practice round this time.</strong>
        </h4>
        <h4>
          If you would like a more detailed review of the instructions,
          click "Review Instructions". Once all three members of your group
          have hit "Next" the task will continue.
        </h4>
        <h4>
          When all three group members have hit Next, the instructions will continue.
        </h4>
        <div class="float-left mt-lg-4">
          <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
        </div>
      </div>
      <div id="inst_3" class="inst">
        <h4>
          As a reminder, each of you will have {{ floor($maxResponses / $groupSize) }}
          guesses. In total, the group has {{ $maxResponses }} guesses.
        </h4>
        <h4>
          After each of you has had 5 guesses, the computer will ask the
          Reporter to input the Group's Best Guess.
        </h4>
        <h4>
          If you need to clarify the rules of the Optimization task you can
          click Review Practice Instructions.
        </h4>
        <h4>
          <strong>Spend a few moments discussing with your group how you will
            tackle the Optimization Task.</strong>
        </h4>
        <h4>
          Remember, there is no practice this time. When all three members hit
          next, <span class="text-danger">the task will begin!</span>
        </h4>
      </div>

      @elseif($intro == 'group_alt_intro')
      <div id="inst_1" class="inst">
        <h4>
          The next task is the same. The only change is that there is a new relationship
          between the numbers you guess and the numbers you receive in return.
        </h4>
        <h4>
          <strong>If you need to, take a moment to discuss with your group, then click "Next" <span class="text-danger">to begin the task</span>.</strong>
        </h4>
      </div>
      @endif
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;"><br />
      </div>

    </div>
  </div>
</div>


@include('layouts.includes.optimization-group-final-answer-prompt')
@include('layouts.includes.optimization-group-reporter-final-answer')
@include('layouts.includes.optimization-group-instructions')
@include('layouts.includes.gather-reporter-modal')
@include('layouts.includes.waiting-for-group')
@include('layouts.includes.one-minute-warning')

<form action="/optimization-individual-final" id="optimization-final-form" style="display:none;" method="post">
  {{ csrf_field() }}
  <div class="form-group">
    <input class="form-control" type="text" name="final_guess" id="final-guess">
    <input type="hidden" name="final_result" id="final-result">
    <input type="hidden" name="function" id="final-function" value="{{ $function }}">
  </div>
</form>

@stop
