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

      $(".alert-danger").hide();

      // Form validation
      // Comes before instructionPaginator so the on click handler is bound first
      $("#next").on('click', function(event) {
        $(".alert-danger").hide();
        $('.input:visible .form-check-input').each(function(){
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
      var channel = pusher.subscribe('my-channel');

      //IF ANOTHER ADMIN MAKES A CHANGE, DYNAMICALLY UPDATE TABLE
      channel.bind('end-subsession', function(data){
        if(data['user']['id'] == '{{ $user->id }}'){
          window.location.href = '/get-group-task';
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
      <h5 class='text-center' >
        @if($type == '1')
          Page 1/2 -- Time Remaining: 
        @else
          Page 2/2 -- Time Remaining: 
        @endif
        <span id='timer'></span>
      </h5>
    </div>
    <div class="col-md-12 text-center">
      <h4 class='text-center'> 
        @if($type == '1' && $user->group_role != 'leader')
          We would like to ask some questions about the person who entered the final guesses (i.e. the 'group leader')
        @elseif($type == '1' && $user->group_role == 'leader')
          We would like to ask some questions about the person who <b>Made Hypotheses</b>
        @elseif($type == '2' && $user->group_role == 'leader')
          We would like to ask some questions about the person who <b>Entered the Equations</b>
        @elseif($type == '2' && $user->group_role != 'leader')
          <b>Thinking about the group I just participated in, I would say that:</b>
        @endif
      </h4>
      <form id="group-survey-form" action="/group-survey" method="post">
        {{ csrf_field() }}

            @for($i = 0; $i < count($questions); $i++)
              <p>{{ $questions[$i]['question'] }}</p>
              <br />
                <div style="display:grid;grid-auto-flow: column;width:100%;margin:auto">
                    <label>1</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="1">
                    <label>2</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="2">
                    <label>3</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="3">
                    <label>4</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="4">
                    <label>5</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="5">
                    <label>6</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="6">
                    <label>7</label>
                    <input type="radio" style="vertical-align: middle;margin-top: -1px;height: 100%;" name="{{ $surveyType }}_$i" value="7">
                </div>
                <div style="display:inline-block;width:100%;margin:auto">
                    <p style="float:left;margin:auto">{{ $questions[$i]['left_text'] }}</p>
                    <p style="float:right;margin:auto">{{ $questions[$i]['right_text'] }}</p>
                </div>
                <hr />
            @endfor
              
      </form>
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;">
        <span class="text-primary ml-md-4 text-lg" id="pagination-display">
          <span id="curr-page">1</span> / {{ ceil(count($statements) / 10) }}
        </span>
      </div>
    </div>
  </div>
</div>

@stop
