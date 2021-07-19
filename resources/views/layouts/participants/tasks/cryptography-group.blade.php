@extends('layouts.bare')

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
var whose_turn = {{ $whose_turn }};
var task_id = {{ $task_id }};
var group_id = {{ $user->group_id }};
var user_id = {{ $user->id }};
var group_role = '{{ $user->group_role }}';
var local_guess = [];
var responses = '';
var time_remaining;


var trialStage = 1;
var trials = 1;
var isReady = true;
var equations = [];
var hypotheses = [];
var mapping_guess = '';
var payment = 8.00;
var guesses = [];
var page = 1;
var tm;

//COUNTDOWN FOR IDLE STATUS
function start_timer(){
  tm = setTimeout(function(){
    $.post('/set-idle', {
      _token: "{{ csrf_token() }}",
      user_id:'{{ $user->id }}'
    });
  },10000);
}

//CLEAR IDLE COUNTDOWN
function clear_timer(){
  clearTimeout(tm);
}


$( document ).ready(function() {
  //GETS TIMER INFO FROM SERVER
  time_remaining = parseInt('{{ $time_remaining }}');

  //GETS INFO ON PRIOR RESPONSES IF USER IS REFRESHING
  responses = $('<div>').html('{{ $responses }}')[0].textContent;
  responses = JSON.parse(responses);

  //IF THERE ARE PRIOR RESPONSES
  if (responses.length  > 0){
    //LOOP THROUGH THEM
    for(var i = 0; i < responses.length; i++){
      //IF LEADER RESPONSE
      if(responses[i]['prompt'].includes('Guess Full Mapping')){
        if(responses[i]['response'].split(', Correct: ')[0].includes(','))
          guesses = responses[i]['response'].split(', Correct: ')[0].split(',');
        else
          guesses = [responses[i]['response'].split(', Correct: ')[0]];
        
        $(".full-mapping").each(function(i, el){          
          $(el).val(guesses[i].split('=')[1]);
        });

        trials++;
        payment -= 0.50;
      }
      //IF FOLLOWER2 RESPONSE
      if(responses[i]['prompt'].includes('Propose Hypothesis')){
        $("#hypothesis-result").append('<h5>' + responses[i]['response'].replace(':','is').replace('=',' = ') + '</h5>');
      }
      //IF FOLLOWER1 RESPONSE
      if(responses[i]['prompt'].includes('Propose Equation')){
        $("#answers").append('<h5 class="answer">' + responses[i]['response'].replace('=',' = ') + '</h5>');
      }
      //IF RULE BROKEN RESPONSE
      if(responses[i]['prompt'].includes('Rule Broken')){
         payment -= 2.00;
      }
    }
    //UPDATE PAYMENT AND TRIAL COUNTER
    $('#payment').text(payment.toFixed(2));
    $("#trial-counter").html(trials);
    
  }

  whose_turn = parseInt(whose_turn);
  //DEFINES RULESET
  task_id = parseInt(task_id);

  //SHOWS DIFFERENT DISPLAY DEPENDING ON TURN
  switch(whose_turn){
    case 0:
      if(group_role == 'follower1'){
        start_timer();
      }
      $('#submit-mapping').attr('disabled',true);
      $('#submit-mapping').text('Waiting for Team');
      $('#submit-hypothesis').attr('disabled',true);
      $('#submit-hypothesis').text('Waiting for Team');
      $('#order-instructions').modal('toggle');
      break;
    case 1:
      if(group_role == 'follower2'){
        start_timer();
      }
      $('#submit-mapping').attr('disabled',true);
      $('#submit-mapping').text('Waiting\nFor Teammates...');
      $('#submit-equation').attr('disabled',true);
      $('#submit-equation').text('Waiting\nFor Teammates...');
      $('#order-instructions').modal('toggle');
      break;
    case 2:
      if(group_role == 'leader'){
        start_timer();
      }
      $('#submit-hypothesis').attr('disabled',true);
      $('#submit-hypothesis').text('Waiting\nFor Teammates...');
      $('#submit-equation').attr('disabled',true);
      $('#submit-equation').text('Waiting\nFor Teammates...');
      $('#order-instructions').modal('toggle');
      break;
    default:
      break;

  }

  $("#alert").hide();
  $("#task-end").hide();

  //RULESETS
  rules = {
    1:[1,4],
    2:[1,7],
    3:[1,8],
    4:[1,9],
    5:[2,10],
    6:[2,11],
    7:[2,12],
    8:[2,15],
    9:[3,4],
    10:[3,5],
    11:[3,6],
    12:[3,7],
    13:[4,8],
    14:[5,9],
    15:[6,10],
    16:[7,11]
  };

  rule_desc = [
    'The first equation must not contain more than 4 letters',
    'The first equation must contain at least 3 letters',
    'The first equation must contain a minus sign',
    'The second equation must contain the letter F',
    'The second equation must contain the letter G',
    'The second equation must contain the letter H',
    'The second equation must contain the letter I',
    'The third equation must NOT contain the letter A',
    'The third equation must NOT contain the letter B',
    'The third equation must NOT contain the letter C',
    'The third equation must NOT contain the letter D',
    'The fourth equation must contain a minus sign',
    'The fourth equation must NOT contain a minus sign',
    'The fifth equation must contain a minus sign',
    'The fifth equation must NOT contain a minus sign',
  ]

  //INITIALIZE CRYPTO GAME WITH SPECIFIED RULESET
  var crypto = new Cryptography(mapping,rules[task_id]);

  $('#rule_1').text(rule_desc[rules[task_id][0] - 1]);
  $('#rule_2').text(rule_desc[rules[task_id][1] - 1])

  //WARNS THEM WHEN THEY HAVE A MINUTE LEFT
  setTimeout(function() {
    $("#timer-warning").modal();
  }, 540 * 1000);

  //PINGS SERVER PERIODICALLY TO CONFIRM STILL ON PAGE
  var itv = setInterval(function() {
    $.get('/still-present', {
      _token: "{{ csrf_token() }}"
    });
  },3000);

  //INITIALIZES PUSHER
  //TO COMMUNICATE WITH SERVER
  Pusher.logToConsole = true;

  var pusher = new Pusher('{{ config("app.PUSHER_APP_KEY") }}', {
    cluster: 'us2'
  });

  //SPECIFIC CHANNEL FOR CRYPTO AND MEMORY
  var channel = pusher.subscribe('task-channel');

  //WHEN ANOTHER PLAYER TAKES THEIR TURN
  channel.bind('action-submitted',function(data){
    //IF THEY ARE IN THIS GROUP (CHANNEL IS SHARED)
    if (data['group_task']['group_id'].toString() == '{{ $user->group_id }}'){
      //HANDLES DEPENDING ON TURN AND ROLE
      switch(data.group_task.whose_turn){
        //IF EQUATION TURN
        case 0:
          //START IDLE TIMER IF USER IS EQUATIONS
          if(group_role == 'follower1'){
            start_timer();
          }

          //END EDLE TIMER IF USER IS LEADER
          if(group_role == 'leader'){
            clear_timer();
          }
          //UPDATE DISPLAY AND COUNTER
          trials++;
          $('#submit-mapping').attr('disabled',true);
          $('#submit-mapping').text('Waiting for Team');
          $('#submit-hypothesis').attr('disabled',true);
          $('#submit-hypothesis').text('Waiting for Team');
          $('#submit-equation').attr('disabled',false);
          $('#submit-equation').text('Submit');
          $("#trial-counter").html(trials);

          //INFORM IF LAST TRIAL
          if(trials == maxResponses)
            $('#last-trial').modal();

          //UPDATE PAYMENT IF CHANGED
          $('#payment').text((((parseFloat($('#payment').text()) - 0.50) > 0.00) ? (parseFloat($('#payment').text()) - 0.50) : 0.00).toFixed(2));

          break;
        //IF HYPOTHESIS TURN
        case 1:
          //START IDLE TIMER IF USER IS HYPOTHESES
          if(group_role == 'follower2'){
            start_timer();
          }

          //END IDLE TIMER IF USER IS EQUATIONS    
          if(group_role == 'follower1'){
            clear_timer();
          }

          //UPDATE DISPLAY
          $('#submit-mapping').attr('disabled',true);
          $('#submit-mapping').text('Waiting for Team');
          $('#submit-equation').attr('disabled',true);
          $('#submit-equation').text('Waiting for Team');
          $('#submit-hypothesis').attr('disabled',false);
          $('#submit-hypothesis').text('Submit');

          break;
        //IF LEADER TURN
        case 2:
          //START IDLE TIMER IF USER IS LEADER
          if(group_role == 'leader'){
            start_timer();
          }
          //START IDLE TIMER IF USER IS HYPOTHESES
          if(group_role == 'follower2'){
            clear_timer();
          }

          //UPDATE DISPLAY
          $('#submit-hypothesis').attr('disabled',true);
          $('#submit-hypothesis').text('Waiting for Team');
          $('#submit-equation').attr('disabled',true);
          $('#submit-equation').text('Waiting for Team');
          $('#submit-mapping').attr('disabled',false);
          $('#submit-mapping').text('Submit');

          break;

        default:
          break;

      }
    }
  });
  
  //IF ANOTHER USER BREAKS RULE
  channel.bind('rule-broken', function(data){
    //IF USER IN THIS GROUP
    if (data['user']['group_id'].toString() == '{{ $user->group_id }}'){
      //INFORM
      $("#rule_broken").modal('toggle');
      //UPDATE PAYMENT
      $('#payment').text((((parseFloat($('#payment').text()) - 2.00) > 0.00) ? (parseFloat($('#payment').text()) - 2.00) : 0.00).toFixed(2));

    }
  });

  //IF ADMIN RUNS CLEAR STORAGE
  //BOOT USER AND CLEAR LOCALSTORAGE
  channel.bind('clear-storage', function(data){
    localStorage.clear();
    window.location.href='/participant-login';
  });

  //IF SUBSESSION IS ENDING, END TASK
  channel.bind('end-subsession', function(data){
    if(data['user']['id'] == user_id && data['order'] == 2){
      $('#cryptography-end-form').submit();
    }
  });

  channel.bind('task-complete', function(data){
    $("#task-result").val(1);
    $("#crypto-header").hide();
    $("#crypto-ui").hide();
    $("#task-end").show();
  });

  //IF ADMIN USES FORCE REFRESH
  channel.bind('force-refresh-user', function(data) {
    if(data['user']['id'].toString() === '{{ $user->id }}'){
      setTimeout(function(){
          window.location.reload();
      },5000);
    }
  });

  //ONCE USER CLICKS OUT OF TIMES UP POPUP, UPDATE INFO AND TOGGLE END OF TASK PROMPT
  $("#ok-time-up").on('click', function(event) {
    localStorage.clear();
    $("#task-result").val(0);
    $("#crypto-header").hide();
    $("#crypto-ui").hide();
    $('#task_end_text').text('Your time is up! Thank you. You will be given credit for any letter values you identified.');
    $("#task-end").show();
    $('#time-up').modal('toggle');
    event.preventDefault();
  });

  //WHEN USER SUBMITS EQUATION
  $("#submit-equation").on("click", function(event) {
    //UPDATE STATUS
    $.post('/set-active', {
      _token: "{{ csrf_token() }}",
      user_id:'{{ $user->id }}'
    });

    event.preventDefault();
    $("#alert").hide();

    //GET INPUT
    var equation = $("#equation").val().toUpperCase().replace(/=/g, '');

    //MUST SUBMIT SOMETHING
    if(equation == '') {
      event.preventDefault();
      $('#invalid_equation').modal('toggle');
      return;
    };

    //CHECK RESPONSE 
    try {
      var res = crypto.parseEquation(equation,trials);
      var answer = res[0];
      var rule_broken = res[1];

      if(rule_broken){
        $.post("/rule-broken", {
          _token: "{{ csrf_token() }}",
          rule_broken: rule_broken
        });
      }

      //ADD EQUATION TO DISPLAY
      $("#answers").append('<h5 class="answer">' + equation + ' = ' + answer + '</h5>');
      $("#equation").val('');

      //SUBMIT RESPONSE TO SERVER
      $.post("/cryptography", {
          _token: "{{ csrf_token() }}",
          prompt: "Propose Equation",
          guess: equation + '=' + answer
        }, function(data) {
          if(data == 'WAIT'){
            $('#submit-equation').text('Waiting for Team');
            $('#submit-equation').attr('disabled',true);
          }
        } 
      );
    }
    //IF INVALID
    catch(e) {
      $('#alert-text-equation').text(e);
      $('#invalid_equation').modal('toggle');
    }
    event.preventDefault();
  });

  //IF USER SUBMITS HYPOTHESIS
  $("#submit-hypothesis").on("click", function(event){
    //UPDATE STATUS
    $.post('/set-active', {
      _token: "{{ csrf_token() }}",
      user_id:'{{ $user->id }}'
    });

    event.preventDefault();

    //BOTH INPUTS MUST BE FILLED
    if ($("#hypothesis-left").val() === '---' || $("#hypothesis-right").val() === '---'){
      $('#invalid-hypothesis').modal('toggle');
      return false;
    }

    //PROCESS RESULT
    var result = crypto.testHypothesis($("#hypothesis-left").val(), $("#hypothesis-right").val());
    var output = (result) ? "true" : "false";

    //UPDATE DISPLAY
    $("#hypothesis-result").append('<h5>' + $("#hypothesis-left").val() + " = " + $("#hypothesis-right").val() + " is " + output + '</h5>');

    //SUBMIT RESPONSE TO SERVER
    $.post("/cryptography", {
        _token: "{{ csrf_token() }}",
        prompt: "Propose Hypothesis",
        guess: $("#hypothesis-left").val() + '=' + $("#hypothesis-right").val() + ' : ' + output
      }, function(data) {
        if(data == 'WAIT'){
          $('#submit-hypothesis').text('Waiting for Team');
          $('#submit-hypothesis').attr('disabled',true);
        }
        isReady = false;
      });

    event.preventDefault();

  });

  //IF USER SUBMITS FULL MAPPING
  $("#submit-mapping").on("click", function(event){
    //UPDATE STATUS
    $.post('/set-active', {
      _token: "{{ csrf_token() }}",
      user_id:'{{ $user->id }}'
    });

    event.preventDefault();

    var result = true;
    var guessStr = '';
    var mappingList = '';
    var mappingArr = [];

    //PROCESS RESPONSE GUESS BY GUESS
    $(".full-mapping").each(function(i, el){
      mappingArr.push($(el).val());
      mappingList += '<span>' + $(el).attr('name') + ' = ' + $(el).val() + '</span>';
      guessStr += $(el).attr('name') + '=' + $(el).val() + ',';
      if(crypto.testHypothesis($(el).attr('name'), $(el).val()) == false) result = false;
    });

    //SUBMIT RESPONSE TO SERVER
    $.post("/cryptography", {
        _token: "{{ csrf_token() }}",
        prompt: "Guess Full Mapping",
        mapping: JSON.stringify(mapping),
        guess: guessStr
      }, function(data) {
        if(data=='WAIT'){
          $('#submit-mapping').text('Waiting for Team');
          $('#submit-mapping').attr('disabled',true);
          isReady = false;
        }
      } 
    );

    //IF MAPPING IS CORRECT, END TASK
    if(result) {
      $("#task-result").val(1);
      $.post('/task-complete', {_token: "{{ csrf_token() }}"});
    }

    //IF THAT WAS LAST TURN, END TASK
    else if (trials == maxResponses) {
      $.post('/task-complete', {_token: "{{ csrf_token() }}"});
    }

    event.preventDefault();

  });

  //CONTROLS FOR INSTRUCTION BOX
  $('#next-button').on('click',function(event){
    if (page == 1 ){
      $('#back-button').css('display','block');
    }
    $('#page'+page.toString()).css('display','none');

    page += 1;

    if (page == 4){
      $('#next-button').css('display','none');
    }
    $('#page'+page.toString()).css('display','block');
    
  });
  
  $('#back-button').on('click',function(event){
    if (page == 4 ){
      $('#next-button').css('display','block');
    }
    $('#page'+page.toString()).css('display','none');

    page -= 1;

    if (page == 1){
      $('#back-button').css('display','none');
    }
    $('#page'+page.toString()).css('display','block');
    
  });


});

</script>

<div class="container" >
  <div class="row" id="crypto-ui">
      <div class="col-sm-12 text-center">
        @if ($user->group_role == "leader")
          <form name="cryptography" id="crypto-form">
            <div class='row'>
              <div class="col-sm-3 " style="border-right:1px solid #DCDCDC">
                <h4 class="text-guess">Current Guesses</h4>
                <div class='col-sm-11' style='float:center;margin:auto' id="mapping-list" >
                  @foreach($sorted as $key => $el)
                    <div style='display:flex'>
                      <span>{{ $el }} = </span>
                      <select data-stop-refresh="true" style='width:55%;margin-left:auto;' class="form-control full-mapping" name="{{ $el }}">
                          <option value='---'>---</option>
                          @for($i = 0; $i < count($sorted); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                          @endfor
                      </select>
                    </div>
                  @endforeach
                </div>
              </div>
              <div id='leader-dashboard' class='col-sm-9'>
                <div class='row'>
                  <div class='col-sm-12'>
                    <h4 style='color:#595959; background:#f2f2f2;margin-right:auto;text-align:left'>LEADER Dashboard</h4>
                  </div>
                </div>
                <div class='row'>
                  <div class='col-sm-9'>
                    <p style='text-align:left'><strong>Equation rules</strong><br />
                    1. <span id='rule_1'>The first equation must contain at least 3 letters</span><br />
                    2. <span id='rule_2'>The fifth equation must NOT contain a minus sign</span><br />
                    <span style='color:red'><i>Breaking a rule costs $2</i></span>
                    </p>
                  </div>
                  <div class='col-sm-3'>
                    <h4 style='text-align:right'><b id='timer'></b></h4>
                  </div>
                </div>

                <p style='text-align:left'><b style='font-size:20px'>LEADER BONUS: $<span id='payment'>5.00</span></b><br>
                <span style='color:red;text-align:left'><i>Each 'trial' costs $0.50</i></span>
                </p><br/>
                <div class='row'>
                  <div class='col-sm-5'>
                    <button style='float:left;' type='button' class="sub-btn btn btn-lg btn-primary" id="submit-mapping" >Submit</button><br />
                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#review-instructions" style='float:left;margin-top:20px'>Review Instructions</button>
                    <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#device-instructions" style='float:left;margin-top:20px'>Mic/Camera Guide</button>
                  </div>
                  <div class='col-sm-7'>
                    <p style='text-align:left;justify-content: left'><i>Click 'submit' <b>after each trial.</b> You do NOT need to guess all the letters to click submit. If you have all the letters correct, the task is complete! You will be able to submit once both of your teammates have finished their turns.</i></p>
                  </div>
                </div>


              </div>
            </div>
          </form>
        @elseif ($user->group_role == 'follower1')
          <form name="cryptography" id="crypto-form">
            <div class='row'>
              <div class="col-sm-7 " style="border-right:1px solid #DCDCDC;min-width:">
                <div class='col-sm-9' style='margin:auto;' id="propose-equation">
                  <h5>Trial <span style='width:7px !important' id="trial-counter">1</span></h5>
                  <h4 class="text-equation">Enter an equation</h4>
                  <h5>Enter the left-hand side of an equation, using letters, addition and
                    subtraction: e.g. “A+B”. Please only use the letters A-J plus '+' and '-'.
                  </h5>
                  <div id="alert" class="alert alert-danger" role="alert"></div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="equation" id="equation">
                  </div>
                </div>
                <div class="text-center">
                  <button type='button' class="sub-btn btn btn-lg btn-primary" id="submit-equation" >Submit</button>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#review-instructions" style='margin-top:10px'>Review Instructions</button>

                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#device-instructions" style='margin-top:10px'>Mic/Camera Guide</button>
                </div>
              </div>
              <div class="col-md-5">
                <h4 class="text-equation">Equation History</h4>
                <div id="answers"></div>
              </div>
            </div>
          </form>

        @elseif ($user->group_role == 'follower2')
          <form name="cryptography" id="crypto-form">
            <div class='row'>
              <div class="col-sm-7 " style="border-right:1px solid #DCDCDC;min-width:">
                <div class='col-sm-9' style='margin:auto;' id="hypothesis">
                    <h5>Trial <span style='width:7px !important' id="trial-counter">1</span></h5>
                    <h4 class="text-hypothesis">Make a hypothesis</h4>
                    <h5>
                      Hypothesize the value of a single letter (e.g. F = 7)
                    </h5>
                    <select class="form-control " id="hypothesis-left">
                        <option>---</option>
                        @foreach($sorted as $key => $el)
                          <option>{{ $el }}</option>
                        @endforeach
                    </select>
                    <span>
                      =
                    </span>
                    <select class="form-control " id="hypothesis-right">
                        <option>---</option>
                        @for($i = 0; $i < count($sorted); $i++)
                          <option>{{ $i }}</option>
                        @endfor
                    </select>
                    <div class="text-center">
                      <button type='button' class="sub-btn btn btn-lg btn-primary" id="submit-hypothesis" >Submit</button>
                    </div>
                    <div class="text-center">
                      <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#review-instructions" style='margin-top:10px'>Review Instructions</button>
                    </div>
                    <div class="text-center">
                      <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#device-instructions" style='margin-top:10px'>Mic/Camera Guide</button>
                    </div>
                </div>
                
              </div>
              <div class="col-md-5">
                <h4 class="text-hypothesis">Hypotheses</h4>
                <div id="hypothesis-result"></div>
              </div>
            </div>
          </form>

        @else
    @endif
    
</div></div>

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
            <span id='task_end_text'>Congratulations! Your solution was correct!</span><br>
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

  <div class="modal fade" id="order-instructions">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body text-center">
          <h5>
            In each round of this task, you and your teammates will complete your assigned steps in this order: submitting an equation, submitting an hypothesis, and submitting a guess at the final answer. <b>If at any point you see that your submit button is disabled and says 'Waiting for Team', this means that someone else on your team is taking their turn. Check in with your teammates if you are ever unsure of whose turn it is.
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

  <div class="modal fade" id="invalid_equation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id='alert-text-equation' class="modal-title text-center">
          The equation you submitted is not valid. Please only use the letters A-J plus '+' and '-'.
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right"  data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->

  <div class="modal fade" id="device-instructions">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="display:block">
          <h4 id='page1'>
          If your teammates can't hear or see you, your browser may be blocking the site from accessing your microphone/camera. </h4>
          <h4 id='page2' style='display:none;'>Check the URL bar at the top of your web browser for a small camera/microphone icon at the far right. Click it and select 'allow' or 'always allow'. If you don't see the icon, that's okay. </h4>
          <h4 id='page3' style='display:none;'>
          Refresh your page. Wait and see if the web browser asks you to allow access to the microphone. Select "allow" and proceed with the task.</h4><br/>
          <h4 id='page4' style='display:none;'>
          If your issue persists, you may need to go into your system prefences / control panel and confirm that you have a working microphone and camera available.
          </h4>
          <div style='display:flex;'>
            <button id='back-button' class='btn btn-lg btn-primary' style='display:none;margin:auto'>Back</button>
            <button id='next-button' class='btn btn-lg btn-primary' style='margin:auto'>Next</button>
          </div>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->

    <div class="modal fade" id="invalid-hypothesis">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 id='alert-text' class="modal-title text-center">
            The hypothesis you submitted is not valid. Please make sure you have selected a letter and a value from the dropdowns.
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right"  data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->

  <div class="modal fade" id="rule_broken">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">
          Your team has submitted an equation which violates one of your equation rules. 
          @if($user->group_role == 'leader')
            Your bonus payment has decreased by $2. 
          @endif
          </h4>
        </div>
        <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary pull-right" id="ok-rule-broken" data-dismiss="modal" type="button">Ok</button>
        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->
@stop
