@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/cryptography.js') }}"></script>
  <script src="{{ URL::asset('js/timer.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>

var mapping = <?php echo  $mapping; ?>;
var maxResponses = {{ $maxResponses }};

var trialStage = 1;
var trials = 1;

$( document ).ready(function() {

  $("#alert").hide();
  $("#hypothesis").hide();
  $("#guess-full-mapping").hide();
  $("#task-end").hide();

  var crypto = new Cryptography(mapping);


  initializeTimer(600, function() {
    $("#crypto-header").hide();
    $("#crypto-ui").hide();
    $("#task-end").show();
    $('#time-up').modal();
  });

  setTimeout(function() {
    $("#timer-warning").modal();
  }, 540 * 1000)

  $("#ok-time-up").on('click', function(event) {
    $("#task-result").val(0);
    $("#crypto-header").hide();
    $("#crypto-ui").hide();
    $("#task-end").show();
    $('#time-up').modal('toggle');
    event.preventDefault();
  })

  $("#submit-equation").on("click", function(event) {

    $("#alert").hide();

    if(trialStage == 1) {

      var equation = $("#equation").val().toUpperCase().replace(/=/g, '');
      if(equation == '') {
        event.preventDefault();
        return;
      };

      $("#hypothesis").hide();
      trialStage++;


      try {
        var answer = crypto.parseEquation(equation);
        $("#answers").append('<h5 class="answer">' + equation + ' = ' + answer + '</h5>');
        $("#equation").val('');
        $('#hypothesis-left option:eq(0)').prop('selected', true);
        $('#hypothesis-right option:eq(0)').prop('selected', true);
        $("#hypothesis").slideDown();
        $("#propose-equation").slideUp();

        $.post("/cryptography", {
            _token: "{{ csrf_token() }}",
            prompt: "Propose Equation",
            guess: equation + '=' + answer
          } );
      }

      catch(e) {
        trialStage = 1;
        $("#alert").html(e);
        $("#alert").show();
      }
    }

    else if(trialStage == 2) {
      trialStage++;
      var result = crypto.testHypothesis($("#hypothesis-left").val(), $("#hypothesis-right").val());
      var output = (result) ? "true" : "false";
      $("#hypothesis-result").append('<h5>' + $("#hypothesis-left").val() + " = " + $("#hypothesis-right").val() + " is " + output + '</h5>');
      $("#hypothesis").slideUp();
      $("#guess-full-mapping").slideDown();

      $.post("/cryptography", {
          _token: "{{ csrf_token() }}",
          prompt: "Propose Hypothesis",
          guess: $("#hypothesis-left").val() + '=' + $("#hypothesis-right").val() + ' : ' + output
        } );
    }

    else if(trialStage == 3) {
      trialStage = 1;
      var result = true;
      var guessStr = '';
      var mappingList = '';

      $(".full-mapping").each(function(i, el){
        mappingList += '<span>' + $(el).attr('name') + ' = ' + $(el).val() + '</span>';
        guessStr += $(el).attr('name') + '=' + $(el).val() + ',';
        if(crypto.testHypothesis($(el).attr('name'), $(el).val()) == false) result = false;
      });

      $("#mapping-list").html(mappingList);
      $.post("/cryptography", {
          _token: "{{ csrf_token() }}",
          prompt: "Guess Full Mapping",
          mapping: JSON.stringify(mapping),
          guess: guessStr
        } );

      if(result) {
        $("#crypto-header").hide();
        $("#crypto-ui").hide();
        $("#task-end").show();
        $("#task-result").val(1);
      }
      else if(trials < maxResponses) {
        trials++;
        $("#trial-counter").html(trials);
        $("#mapping-result").html('Your guess of the full mapping was incorrect.');
        $("#mapping-result").show();
        $("#guess-full-mapping").slideUp();
        $("#propose-equation").slideDown();

        if(trials == maxResponses) {
          $('#last-trial').modal();
        }
      }

      else {
        $("#crypto-header").hide();
        $("#crypto-ui").hide();
        $("#task-end").show();
      }
    }

    event.preventDefault();
  });


});

</script>

<div class="container">
  <div class="row" id="crypto-header">
    <div class="col-md-12 text-center">
      <div class="float-right text-primary" id="timer"></div>
      <h3 class="mb-lg-4 header">
        Cryptography Task
      </h3>
    </div>
  </div>
  <div class="row" id="crypto-ui">
      <div class="col-md-5 text-center">
      <h5>Trial <span id="trial-counter">1</span> of {{ $maxResponses }}</h5>
      <form name="cryptography" id="crypto-form">

        <div id="propose-equation">
          <h4 class="text-primary" id="mapping-result"></h4>
          <h4 class="text-equation">Enter an equation</h4>
          <h5>Enter the left-hand side of an equation, using letters, addition and
            subtraction: e.g. “A+B”. Please only use the letters A-J plus '+' and '-'.
          </h5>
          <div id="alert" class="alert alert-danger" role="alert"></div>
          <div class="form-group">
            <input type="text" class="form-control form-control-lg" name="equation" id="equation">
          </div>
        </div>

        <div id="hypothesis">
          <h4 class="text-hypothesis">Make a hypothesis</h4>
          <h5>
            Hypothesize the value of a single letter (e.g. F = 7)
          </h5>
          <select class="form-control propose" id="hypothesis-left">
              <option>---</option>
              @foreach($sorted as $key => $el)
                <option>{{ $el }}</option>
              @endforeach
          </select>
          <span>
            =
          </span>
          <select class="form-control propose" id="hypothesis-right">
              <option>---</option>
              @for($i = 0; $i < count($sorted); $i++)
                <option>{{ $i }}</option>
              @endfor
          </select>
        </div>
        <!-- <div class="text-primary" id="hypothesis-result"></div> -->

        <div id="guess-full-mapping">
              <h4 class="text-guess">Guess the letter values</h4>
              <h5>
                Guess as many letter values as you want, then click submit to
                start the next trial.
              </h5>
              @foreach($sorted as $key => $el)
                <span>{{ $el }} = </span>
                <select class="form-control full-mapping" name="{{ $el }}">
                    <option>---</option>
                    @for($i = 0; $i < count($sorted); $i++)
                      <option>{{ $i }}</option>
                    @endfor
                </select>

              @endforeach
        </div>

        <div class="text-center">
          <button class="btn btn-lg btn-primary" id="submit-equation" type="submit">Submit</button>
        </div>
        <div class="float-left mt-lg-4">
          <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#review-instructions">Review Instructions</button>
        </div>
        <!-- <div id="answers"></div> -->
      </form>
      </div>
      <div class="col-md-3 crypto-result">
        <h4 class="text-equation">Equations</h4>
        <div id="answers"></div>
      </div>

      <div class="col-md-2 crypto-result">
        <h4 class="text-hypothesis">Hypotheses</h4>
        <div id="hypothesis-result"></div>
      </div>

      @if(Auth::user()->role_id == 3)
        <div class="col-md-2 crypto-result">
          <h4 class="text-guess">Current Guesses</h4>
          <div id="mapping-list">
            <span>A = ---</span>
            <span>B = ---</span>
            <span>C = ---</span>
            <span>D = ---</span>
            <span>E = ---</span>
            <span>F = ---</span>
            <span>G = ---</span>
            <span>H = ---</span>
            <span>I = ---</span>
            <span>J = ---</span>
          </div>
        </div>
      @endif
    </div>
    <div class="row vertical-center" id="task-end">
      <div class="col-md-8 offset-md-2">
        @if($isReporter)
          <form action="/cryptography-end" id="cryptography-end-form" method="post">
        @else
          <form action="/cryptography-end" id="cryptography-end-form" method="post">
        @endif
          {{ csrf_field() }}
          <input type="hidden" name="task_result" id="task-result" value="0">
          <h3 class="text-center">
            You have completed the Cryptography Task.<br>
            Press the button below to continue
          </h3>
          <div class="text-center">
            <button class="btn btn-lg btn-primary" id="continue" type="submit">Continue</button>
          </div>
        </form>
      </div>
  </div>

  <div class="modal fade" id="last-trial">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">
          This is your last trial. The guesses you submit at the end of the
          trial will be your final answer. Remember, you get points for all
          the letter values you correctly identify
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" id="ok-last-trial" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->

  <div class="modal fade" id="review-instructions">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body text-center">
          <h5>
          Each letter from A to J has a value from 0 to 9. Each letter has a
          different value. Your goal is to uncover the value of each letter by
          using “trials”. A trial has three steps. First you <span class="text-equation">enter an equation</span>
          (e.g. “A+B”). You can only use addition and subtraction. Second, you
          <span class="text-hypothesis">make a hypothesis</span> (e.g. “D=4”) and the computer will tell you if this
          hypothesis is TRUE or FALSE. Third, you can <span class="text-guess">guess</span> the values of each
          letter. You don’t have to make guesses for all the letters.
          </h5>
          <h5>
            Try to find out the value of each letter WITH AS FEW TRIALS AS
            POSSIBLE. You have {{ $maxResponses }} trials and 10 minutes. If you run out of
            trials, or time, you will get some points for any of the letters
            you have correctly identified.
          </h5>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->

  <div class="modal fade" id="timer-warning">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">
          You have one minute remaining.
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" id="ok-timer-warning" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->

  <div class="modal fade" id="time-up">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">
          Your time is up. You will get points for your current guesses
          that are correct.
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" id="ok-time-up" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->
@stop
