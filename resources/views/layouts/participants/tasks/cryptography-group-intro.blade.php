
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
  <script src="{{ URL::asset('js/cryptography.js') }}"></script>


  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">

<script>
  var mapping = ['J', 'A', 'H', 'C', 'G', 'B', 'E', 'I', 'F', 'D'];
  var trialStage = 1;
  var hypothesisCount = 0;
  var user_id = {{ $user->id }};
  var page_count = 1;

$( document ).ready(function() {

  $(".alert").hide();
  $(".next-prompt").hide();


   Pusher.logToConsole = true;

    var pusher = new Pusher('7475be7ce897a0019304', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('task-channel');
    channel.bind('all-ready', function(data) {
      $('#next').attr('disabled',false);
      $("#inst_" + page_count).hide();
      page_count += 1;
      //alert(JSON.stringify(data));
          if(page_count > $(".inst").length){
            console.log('longer');

            $("#pagination-display").hide();
            $('.instr_nav').hide();
            //$("#waiting").show();

            channel.unbind('all-ready');

            $.get('/cryptography', function(data) {
              $('#content').html(data);
            });
          }

          // Show the new instruction
          $("#inst_"+page_count).show();

          // Hide back button if we're at the start
          if(page_count <= 1){
            $("#instr_nav #back").hide();
          }

          else {
            $("#instr_nav #back").show();
          }

          // If there is a page # display, update it
          if($("#pagination-display").length) {

            $("#curr-page").html(page_count);
          }

          $('#next').val('Next');

          event.preventDefault();
          
    });

  

  instructionPaginator(function(){window.location = '/cryptography';});

});

</script>
<p>{{ $user->participant_id }}</p>

      @if($introType == 'group_1' || $introType == 'group_2')
      <div id="inst_1" class="inst">
        <h3 class="text-primary">Welcome to your new group</h3>
        <h4>
          You will be working together for 10-12 minutes, trying to solve the GROUP CRYPTOGRAPHY puzzle.
        </h4>
        <h4>
          Please take a moment to introduce yourselves.
        </h4>
      </div> <!-- End inst_1 -->
      <div id="inst_2" class="inst">
        <h3 class="text-primary">Overview</h3>
        <h4>
          This task is very similar to the cryptography task you did as an invidividual.
        </h4>
        <h4>
          Now, you will be working on the task <strong>as a group</strong>.
        </h4>
        <h4>
          You each have a specific role.
        </h4>
      </div> <!-- End inst_2 -->
      <div id="inst_3" class="inst">
        <h3 class="text-primary">Review of cryptography</h3>
        <h4>
          Recall that in the Cryptography Task, every letter from A to J has a numerical value. The goal is to find out the value of each letter.
        </h4>
        <h4>
          You do this through 'trials'. A trial has three steps:<br />
          1. <span style='color:purple'>Enter an equation</span> (e.g. CC + B - A = ?)<br />
          2. <span style='color:red'>Make a hypothesis</span> (e.g. C = 1)<br/>
          3. <span style='color:green'>Guess the letter values</span>
        </h4>
        <h4>
          Your goal is to solve the puzzle using <strong>the SMALLEST number of trials.</strong> This is how you get a good score.
        </h4>
      </div> <!-- End inst_3 -->
      <div id="inst_4" class="inst">
        <h3 class="text-primary">Instructions</h3>
        @if ($user->group_role == "leader")
          <h4>
            You are the group's <strong>leader</strong>
          </h4>
          <h4>
            You are responsible for <span style='color:purple'>guessing the final letter values</span> for each letter (e.g. A=4, B=2)
          </h4>
          <h4>
            This is the <strong>last step</strong> in each 'trial'.
          </h4>
          <h4>
            You are also responsible for making sure that the group follows the <strong>"equation rules"</strong>.
          </h4>
          <h4>
            Each time your group breaks a rule, you pay a penalty of $2.
          </h4>
        @endif
        @if ($user->group_role == "follower1")
         <h4>
            You have been assigned the role of <span style='color:red'>entering equations.</span>
          </h4>
          <h4>
            An 'equation' is a combination of letters with + and - (<strong>you can't multiply or divide</strong>). For example, you might enter A + B.
          </h4>
          <h4>
            Entering an equation is the <strong>first step</strong> in each 'trial'.
          </h4>
          <h4>
            Feel free to discuss your equation with your group before entering it.
          </h4>
        @endif
        @if($user->group_role == 'follower2')
          <h4>
            You have been assigned the role of <span style='color:green'>making hypotheses</span>
          </h4>
          <h4>
            This is the part of each 'trial' where you can get feedback from the computer about one letter. For example, you might hypothesize that C = 3, and the computer would tell you whether your guess is correct.
          </h4>
          <h4>
            This is the <strong>second step</strong> in each 'trial'.
          </h4>
          <h4>
            Feel free to discuss your hypothesis with your group before entering it.
          </h4>
        @endif
      </div> <!-- End inst_4 -->
      
      
      <div id="instr_nav" class="text-center" style='display:flex'>
        <input style='text-align:center;margin:auto' class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next"><br />
        
      </div>
      @endif

