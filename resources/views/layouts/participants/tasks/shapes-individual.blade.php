@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
  <script src="{{ URL::asset('js/timer.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')

<script>

    var numShapes = {{ $shapes['length'] }};
    $( document ).ready(function() {

      $("#timer-submit").on("click", function(event) {
        $("#shapes-form").submit();
        event.preventDefault();
      });


      @if($subtest == 'subtest1')
        var time = 180;
      @elseif($subtest == 'subtest2')
        var time = 240;
      @elseif($subtest == 'subtest3' || $subtest == 'subtest4')
        var time = 180;
      @elseif($subtest == 'subtest5')
        var time = 420;
      @endif

      initializeTimer(time, function() {
        $('#submitPrompt').modal();
      });

      // Tracking clicks
      var lastClick = new Date();
      var timingList = "presented_at: " + lastClick.toLocaleString();

      $(".timing-input").on('change', function() {
        let now = Date.now();
        let clickTime = now - lastClick;
        let val = $(this).val();
        timingList += ', ' + val + ': ' + clickTime;
      });

      $(".tracking-submit").click(function(event) {
        timingList += ', submitted_at: ' + new Date().toLocaleString() + ' ';
        let el = $(".timing-input:visible").attr('name').replace('[]', ''); // Removing array brackets from name
        let currVal = $("input[name='timing_"+el+"']").val();
        $("input[name='timing_"+el+"']").val(currVal + timingList);

        lastClick = new Date();
        timingList = "presented_at: " + lastClick.toLocaleString();
      });

      $("#back").on('click', function(event) {
        if(parseInt($("#curr-page").html() - 1) <= numShapes) {
          $("#next").show();
          $("#pagination-display").show();
        }
      });

      $("#next").on('click', function(event) {
        if(parseInt($("#curr-page").html()) == numShapes) {
          $("#next").hide();
          $("#pagination-display").hide();
        }
      });

      instructionPaginator(function(){

      });
    });

</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <h3><div class="float-right text-primary" id="timer"></div></h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <form id="shapes-form" action="/shapes-individual" method="post">
        {{ csrf_field() }}
        @for($i = 1; $i <= $shapes['length']; $i++)
          @if($subtest == 'subtest1')
            <div id="inst_{{ $i }}" class="inst">
              <img src="/img/shapes-task/subtest1/{{ $i }}.png" class="img-fluid shapes-img" style="width: 700px!important;">
              <div class="row">
                <div class="col-md-2 offset-md-5">
                  <div class="form-group mb-lg-5">
                    <label for="{{ $i }}">Your answer:</label>
                    <select class="form-control ml-lg-2 timing-input" name="{{ $i }}">
                      <option>------</option>
                      <option value="a">a</option>
                      <option value="b">b</option>
                      <option value="c">c</option>
                      <option value="d">d</option>
                      <option value="e">e</option>
                      <option value="f">f</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          @else

          <div id="inst_{{ $i }}" class="inst">
            <h4>
              @if($subtest == 'subtest2')
                Please select <strong>two</strong> boxes
              @elseif($subtest == 'subtest3')
                Choose the box that best fits in the empty dotted box
              @elseif($subtest == 'subtest4')
                Select the "match" for the top box (look for a shape where you
                could put a dot in the same place as it is in the top box)
              @endif
            </h4>
            <div class="text-center shapes-test-container shapes-{{ $subtest }}">
              @if($subtest != 'subtest5')
                <img src="/img/shapes-task/{{ $subtest }}/{{ $i }}.png" class="shapes-img">
              @endif
              <table class="table shapes-test-table shapes-{{ $subtest }}">
                <tr>
                  @if($subtest == 'subtest5')
                  <td>
                    <img src="/img/shapes-task/{{ $subtest }}/{{ $i }}.png" class="shapes-img">
                  </td>
                  @endif
                  @if($subtest == 'subtest2')
                    @for($j = 0; $j < 5; $j++)
                      <td>
                        <input class="form-check-large check-limited timing-input" type="checkbox" name="{{ $i }}[]" value="{{ strtolower(chr($j + 65)) }}">
                      </td>
                    @endfor
                  @elseif($subtest == 'subtest3')
                    @for($j = 0; $j < 6; $j++)
                      <td>
                        <input class="form-check-large timing-input" type="radio" name="{{ $i }}[]" value="{{ strtolower(chr($j + 65)) }}">
                      </td>
                    @endfor
                  @elseif($subtest == 'subtest4')
                    @for($j = 0; $j < 5; $j++)
                      <td>
                        <input class="form-check-large timing-input" type="radio" name="{{ $i }}[]" value="{{ strtolower(chr($j + 65)) }}">
                      </td>
                    @endfor
                  @elseif($subtest == 'subtest5')
                    <td class="align-middle">
                        <h4>
                        What is the <span style="color: #ea3767;">missing piece</span>?<br>
                        Select your answer here
                      </h4>
                        <select class="form-control form-control-lg timing-input" style="width: 120px; margin: 0 auto;" name="{{ $i }}[]">
                          <option value="">----</option>
                          <option>1</option>
                          <option>2</option>
                          <option>3</option>
                          <option>4</option>
                          <option>5</option>
                          <option>6</option>
                          <option>7</option>
                          <option>8</option>
                        </select>
                    </td>
                  @endif
                  <input type="hidden" name="timing_{{ $i }}">
                </tr>
              </table>
            </div>

          </div>

          @endif

        @endfor
        <div id="inst_{{ $shapes['length'] + 1 }}" class="inst mb-lg-5">
          <h3>
            You may submit your answers for this test, or use the back button
            to review and change your answers.
          </h3>
          <input class="btn btn-primary btn-lg" type="submit" value="Submit Answers"><br />
        </div>
      </form>
      <div id="instr_nav" class="text-center"
        @if($subtest == 'subtest5')
          style="width: 400px; top: -30%; position: relative; left: 63%;"
        @endif
      >
        <input class="btn btn-primary instr_nav btn-lg tracking-submit" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg tracking-submit" type="button" name="next" id="next" value="Next &#8680;"><br />
        <span class="text-primary ml-md-4 text-lg" id="pagination-display">
          <span id="curr-page">1</span> / {{ $shapes['length'] }}
        </span>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="submitPrompt" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center">Your time is up.<br>Please submit your responses now</h4>
      </div>
      <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary" id="timer-submit" type="button">Submit</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop
