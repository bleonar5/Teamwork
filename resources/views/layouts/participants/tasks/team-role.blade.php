@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/timer.js') }}"></script>
  <script src="{{ URL::asset('js/teamRolePaginator.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')

<script>

    var scenario = 1;

    $( document ).ready(function() {

      $(".alert-danger").hide();
      $("#second-scenario-intro").hide();

      $("#next").on('click', function(event) {
        //Form validation
        $(".alert-danger").hide();
        $('.input:visible .form-check-input').each(function(){
          var name = $(this).attr("name");
          if ($("input:radio[name=" + name + "]:checked").length == 0) {
            $(".alert-danger").show();
            event.stopImmediatePropagation();
            return;
          }
        })
        event.preventDefault();
      });

      // If next is clicked when they leave a question section
      $("#next").on('click', function(event) {
        if($("table.team-role").is(":visible")) {
          // Show popup alerting them there are more scenarios
          if(scenario == 1) {
            $(".inst").hide();
            $("#instr_nav").hide();
            $("#second-scenario-intro").show();
            //$("#scenario1Complete").modal();
            event.stopImmediatePropagation();
          }
        }
      });

      instructionPaginator(function(){
        $(".container").hide();
        $("#team-role-responses")[0].submit();
      });

      $("#next-scenario").on('click', function(event){
        if(scenario == 1) {
          scenario++;
          goToPage(2); // The page that you want them to move on from
          $(".inst").hide();
          $("#second-scenario-intro").hide();
          $("#instr_nav").show();
          $("#next").click();
        }
        event.preventDefault();
      });

    });

</script>

<div class="container container-large">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <form id="team-role-responses" name="team-role-responses" action="/team-role" method="post">
        {{ csrf_field() }}


        @for($i = 1; $i <= count($scenarios); $i++)

          <div id="inst_{{ $i * 2 - 1 }}" class="inst scenario">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <h4 class="text-left mb-lg-4">
                  Please read the following scenario carefully:
                </h4>
                <h4>
                  <ul class="text-left">
                    @foreach($scenarios[$i - 1]['desc'] as $desc)
                      <li>{{ $desc }}</li>
                    @endforeach
                  </ul>
                </h4>
                <h4 class="text-primary">
                  Instructions: On the next page are 10 potential responses to this situation.
                  Your job is to rate how effective each response would be.
                  If you need to, you can return to this page to refresh your
                  memory about the scenario.
                </h4>
              </div>
            </div>
          </div>
          <div id="inst_{{ $i * 2 }}" class="inst responses">
            <h3 class="text-center">
              Scenario {{ $i }}: how <strong>effective</strong> would the
            following actions be?
            </h3>
            <h5>
              Click "Back to description" at the bottom of the screen to
              review the scenario.
            </h5>
            <table class="team-role table table-striped table-sm">
              <tr>
                <td class="blank"></td>
                @foreach($scenarios[$i - 1]['key'] as $key)
                  <th class="key">
                    <div><span>{{ $key }}</span></div>
                  </th>
                @endforeach
              </tr>

              @foreach($scenarios[$i - 1]['responses'] as $key => $response)
                <tr>
                  <td class="text-left">
                    {{ $response['response'] }}
                  </td>
                  @for($j = count($scenarios[$i - 1]['key']); $j > 0; $j--)
                    <td class="text-center input">
                    <!--  <div class="form-check form-check-inline"> -->
                        <input class="form-check-input radio-large" type="radio" name="scenario_{{ $i - 1 }}_response_{{ $key }}" value="{{ $j }}">
                    <!--   </div> -->
                    </td>
                  @endfor
                </tr>
              @endforeach
            </table>
          </div>
        @endfor
      </form>
      <div class="alert alert-danger">
        <h6>Please be sure to answer each question before continuing.</h6>
      </div>
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="Back to scenario description">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next"><br />
      </div>

      <div id="second-scenario-intro">
        <h4>
          We will now present you with the second scenario.
        </h4>
        <h4>Click "Next"</h4>
        <div class="text-center">
            <button class="btn btn-lg btn-primary" id="next-scenario" type="button">Next</button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="scenario1Complete" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Thank you.
          @if(count($scenarios) - 1 == 1)
            There is {{ count($scenarios) - 1 }} more scenario. You have a total of 4 minutes to complete it.
          @else
            There are {{ count($scenarios) - 1 }} more scenarios. You have a total of 4 minutes to complete them.
          @endif
        </h4>
      </div>
      <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary" id="next-scenario" type="button">Continue</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="timerComplete" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">
          Your time is up.<br>You will now move on to the next scenario.
        </h4>
      </div>
      <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary" id="timer-complete" type="button">Continue</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
