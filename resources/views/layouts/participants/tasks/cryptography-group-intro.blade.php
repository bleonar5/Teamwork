
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/cryptoPaginator.js') }}"></script>
  <script src="{{ URL::asset('js/cryptography.js') }}"></script>


  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">

<script>
  //var mapping = ['J', 'A', 'H', 'C', 'G', 'B', 'E', 'I', 'F', 'D'];
  var trialStage = 1;
  var hypothesisCount = 0;
  var group_id  = {{ $user->group_id }};
  var user_id = {{ $user->id }};
  var page_count;

$( document ).ready(function() {
  page_count = 1;

  var itv = setInterval(function() {
      console.log('GOING OFF');
      $.get('/still-present', {
        _token: "{{ csrf_token() }}"
      });
    },7000);

  

  $(".alert").hide();
  $(".next-prompt").hide();


   Pusher.logToConsole = true;

    var pusher = new Pusher('{{ config("app.PUSHER_APP_KEY") }}', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('task-channel');

    channel.bind('end-subsession', function(data){
      //alert('The next round is beginning soon. You will be sent to the waiting room to be matched with a new team.')
      //setTimeout(function(){
      //    $('#cryptography-end-form').submit();
      //    
      //},5000)
      window.location.href='/end-intro';
    });

    channel.bind('force-refresh', function(data) {
      //console.log(data['group_task']['group_id']);
      //console.log('{{ $user->group_id }}');
      if(data['group_task']['group_id'].toString() === '{{ $user->group_id }}'){
        alert('In a few seconds, your page will refresh. Your progress in the task will be preserved.');
        setTimeout(function(){
            window.location.reload();
        },5000);
      }
        
        


        //$('#waitingList').append("<li style='text-align:left' id='"+data['user']['id'].toString()+"'>"+data['user']['id']+" : "+data['user']['group_role']+"</li>");
    });

    channel.bind('clear-storage', function(data){
      console.log('freedom!');
      localStorage.clear();
      window.location.href='/participant-login';
    });
    channel.bind('all-ready', function(data) {
      if(data['user']['group_id'] == group_id){
        $('#next').attr('disabled',false);
        $("#inst_" + page_count).hide();
        page_count += 1;
        localStorage.setItem('pageCount',page_count);
        //alert(JSON.stringify(data));
            if(page_count > $(".inst").length){
              console.log('longer');

              $("#pagination-display").hide();
              $('.instr_nav').hide();
              //$("#waiting").show();

              channel.unbind('all-ready');
              localStorage.setItem('pageCount',1);

              $.get('/end-group-task', function(data) {
                //console.log(data);
                window.location.href= '/task-room';
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
        }

    });

  

  instructionPaginator(function(){window.location = '/cryptography';});

  if (localStorage.getItem('pageCount')){
    console.log('%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%');
    
    page_count = parseInt(localStorage.getItem('pageCount'),10);
    console.log(page_count);
    $("#inst_1").hide();
    $("#inst_"+page_count).show();
  }
  else{
    localStorage.setItem('pageCount',page_count);
  }

});

</script>

      @if($introType == 'group_1' || $introType == 'group_2')
      <div id="inst_1" class="inst">
        <h4 class="text-primary">Welcome to your new group</h4>
        <h5>
          You will be working together for 10-12 minutes, trying to solve the GROUP CRYPTOGRAPHY puzzle.
        </h5>
        <h5>
          Please take a moment to introduce yourselves.
        </h5>
      </div> <!-- End inst_1 -->
      <div id="inst_2" class="inst">
        <h4 class="text-primary">Overview</h4>
        <h5>
          This task is very similar to the cryptography task you did as an invidividual.
        </h5>
        <h5>
          Now, you will be working on the task <strong>as a group</strong>.
        </h5>
        <h5>
          You each have a specific role.
        </h5>
      </div> <!-- End inst_2 -->
      <div id="inst_3" class="inst">
        <h4 class="text-primary">Review of cryptography</h4>
        <h5>
          Recall that in the Cryptography Task, every letter from A to J has a numerical value. The goal is to find out the value of each letter.
        </h5>
        <h5>
          You do this through 'trials'. A trial has three steps:<br />
          1. <span style='color:purple'>Enter an equation</span> (e.g. CC + B - A = ?)<br />
          2. <span style='color:red'>Make a hypothesis</span> (e.g. C = 1)<br/>
          3. <span style='color:green'>Guess the letter values</span>
        </h5>
        <h5>
          Your goal is to solve the puzzle using <strong>the SMALLEST number of trials.</strong> This is how you get a good score.
        </h5>
      </div> <!-- End inst_3 -->
      <div id="inst_4" class="inst">
        <h4 class="text-primary">Instructions</h4>
        @if ($user->group_role == "leader")
          <h5>
            You are the group's <strong>leader</strong>
          </h5>
          <h5>
            You are responsible for <span style='color:purple'>guessing the final letter values</span> for each letter (e.g. A=4, B=2)
          </h5>
          <h5>
            This is the <strong>last step</strong> in each 'trial'.
          </h5>
          <h5>
            You are also responsible for making sure that the group follows the <strong>"equation rules"</strong>.
          </h5>
          <h5>
            Each time your group breaks a rule, you pay a penalty of $2.
          </h5>
        @endif
        @if ($user->group_role == "follower1")
         <h5>
            You have been assigned the role of <span style='color:red'>entering equations.</span>
          </h5>
          <h5>
            An 'equation' is a combination of letters with + and - (<strong>you can't multiply or divide</strong>). For example, you might enter A + B.
          </h5>
          <h5>
            Entering an equation is the <strong>first step</strong> in each 'trial'.
          </h5>
          <h5>
            Feel free to discuss your equation with your group before entering it.
          </h5>
        @endif
        @if($user->group_role == 'follower2')
          <h5>
            You have been assigned the role of <span style='color:green'>making hypotheses</span>
          </h5>
          <h5>
            This is the part of each 'trial' where you can get feedback from the computer about one letter. For example, you might hypothesize that C = 3, and the computer would tell you whether your guess is correct.
          </h5>
          <h5>
            This is the <strong>second step</strong> in each 'trial'.
          </h5>
          <h5>
            Feel free to discuss your hypothesis with your group before entering it.
          </h5>
        @endif
      </div> <!-- End inst_4 -->
      
      
      <div id="instr_nav" class="text-center" style='display:flex'>
        <input style='text-align:center;margin:auto' class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next"><br />
        
      </div>
      @endif

