@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')

<script>

    $( document ).ready(function() {

      var time_remaining = parseInt('{{ $time_remaining }}');

      itv = setInterval(function(){
        time_remaining -= 1;
        //TIMER CAN'T BE LESS THAN 0        
        $('#timer').text(time_remaining > 0 ? new Date(time_remaining * 1000).toISOString().substr(14, 5) : '00:00');
      },1000);

      $(".alert-danger").hide();

      // Form validation
      // Comes before instructionPaginator so the on click handler is bound first
      $("#next").on('click', function(event) {
        $(".alert-danger").hide();
        $('input[class="checkbox"]:visible').each(function(){
          var name = $(this).attr("name");
          if ($("input:radio[name=" + name + "]:checked").length == 0) {
            $(".alert-danger").show();
            event.stopImmediatePropagation();
            return;
          }
        })
      });


      instructionPaginator(function(){
        $(".container").hide();
        $("#group-survey-form").submit();
      });

      //ESTABLISHES PUSHER CONNECTION
      //TO COMMUNICATE WITH SERVER    
      Pusher.logToConsole = true;

      var pusher = new Pusher('{{ config("app.PUSHER_APP_KEY") }}', {
        cluster: 'us2'
      });

      //CHANNEL FOR ADMIN AND WAITING ROOM
      var channel = pusher.subscribe('task-channel');

      //IF ANOTHER ADMIN MAKES A CHANGE, DYNAMICALLY UPDATE TABLE
      channel.bind('end-subsession', function(data){
        if(data['user']['id'] == '{{ $user->id }}'){
          $('#group-survey-form').submit();
        }
      });


    });

</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="alert alert-danger" role="alert">Please make sure you answer all questions before continuing.</div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <h4 class='text-center' >
        Time to Complete Survey: 
        <span id='timer' style='color:red'>{{ gmdate('i:s',$time_remaining) }}</span>
      </h4>
    </div>
    <div class="col-md-8 text-center" style='margin:auto'>
      <form id="group-survey-form" action="/group-survey" method="post">
        {{ csrf_field() }}
        <div class = 'inst' id='inst_1'>
          <h4 class='text-center'> 
            <span id='pagetoptext'></span>
            @if($user->group_role != 'leader')
              We would like to ask some questions about your group's <b>LEADER</b> (who entered the groups guesses for all the letter values)
            @else
              We would like to ask some questions about the person who <b>Made Hypotheses</b> (e.g. guessed things like A=7)
            @endif
          </h4>
          <hr />
          @for($i = 0; $i < count($questions['1']); $i++)
            <p style='display:flex'><b>{{ $questions['1'][$i]['question'] }}</b></p>
              <div style="display:grid;grid-auto-flow: column;width:100%;margin:auto">
                  <label>1</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="1">
                  <label>2</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="2">
                  <label>3</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="3">
                  <label>4</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="4">
                  <label>5</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="5">
                  <label>6</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="6">
                  <label>7</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="7">
              </div>
              <div style="display:inline-block;width:100%;margin:auto">
                  <p style="float:left;margin:auto"><i>{{ $questions['1'][$i]['left_text'] }}</i></p>
                  <p style="float:right;margin:auto"><i>{{ $questions['1'][$i]['right_text'] }}</i></p>
              </div>
              <hr />
          @endfor
        </div>

        <div class = 'inst' id='inst_2'>
          <h4 class='text-center'> 
            <span id='pagetoptext'></span>
            @if($user->group_role == 'leader')
              We would like to ask some questions about the person who <b>Entered the Equations</b> (e.g. C+D+F=?)
            @else
              <b>Thinking about the group I just participated in, I would say that:</b>
            @endif
          </h4>
          <hr />
          @for($i = 0; $i < count($questions['2']); $i++)
            <p style='display:flex'><b>{{ $questions['2'][$i]['question'] }}</b></p>
              <div style="display:grid;grid-auto-flow: column;width:100%;margin:auto">
                  <label>1</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="1">
                  <label>2</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="2">
                  <label>3</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="3">
                  <label>4</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="4">
                  <label>5</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="5">
                  <label>6</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="6">
                  <label>7</label>
                  <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 30px;width:30px;" name="{{ $surveyType }}_{{ $i }}" class="checkbox" value="7">
              </div>
              <div style="display:inline-block;width:100%;margin:auto">
                  <p style="float:left;margin:auto">{{ $questions['2'][$i]['left_text'] }}</p>
                  <p style="float:right;margin:auto">{{ $questions['2'][$i]['right_text'] }}</p>
              </div>
              <hr />
          @endfor
        </div>
        

            
              
      </form>
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;">
        <span class="text-primary ml-md-4 text-lg" id="pagination-display">
          <span id="curr-page">1</span> / 2
        </span>
      </div>
    </div>
  </div>
</div>

@stop
