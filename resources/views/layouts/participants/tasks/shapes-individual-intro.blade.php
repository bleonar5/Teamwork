@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('content')

<script>
    $( document ).ready(function() {
      instructionPaginator(function(){ window.location = '/shapes-individual';});
    });
</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
        @if($subtest == 'subtest1')
          <div id="inst_1" class="inst">
            <h2 class="text-primary">Shapes Task</h2>
            <h3 class="text-success">
              Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
            </h3>
            <h3>
              This task is about understanding patterns and identifying what
              comes next. We’ll start with a practice.
            </h3>
          </div>
          <div id="inst_2" class="inst">
            <h3>
              On the top, there are four boxes.
              The last one is empty. Below you see six
              more boxes, marked a, b, c, d, e, and f. Of those six boxes,
              one will fit correctly in the empty box.
            </h3>
            <h3>
              The correct answer has been
              given to you in this first example. It’s box c.
            </h3>
            <img src="/img/shapes-task/subtest1/example_01.png" class="img-fluid">
          </div>
          <div id="inst_3" class="inst">
            <h3>
              Here's another example. The black part comes down lower and lower
              each time. So at the next step it would come more than half way down.
              The correct answer for the second example is box e.
            </h3>
            <img src="/img/shapes-task/subtest1/example_02.png" class="img-fluid">
          </div>
          <div id="inst_4" class="inst">
            <h3>
              After clicking the “next” button you will be asked to complete the
              rest yourself. As in the practices, select the box that continues
              the pattern!
            </h3>
            <h3>
              There are 13 questions and you have 3 minutes. You may not have time
              to finish all the questions, but work as quickly and carefully as you
              can. There will be a timer in the top right of your screen.
            </h3>
            <h3>
              You may change your answer if you change your mind, but not after the
              3 minutes is up.
            </h3>
          </div>

        @elseif($subtest == 'subtest5')
          <div id="inst_1" class="inst">
            <h2 class="text-primary">Shapes Task</h2>
            <h3 class="text-success">
              Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
            </h3>
            <h4>
              This task is about understanding patterns and identifying what
              comes next.
            </h4>
          </div>
          <div id="inst_2" class="inst">
            <h4>
              In this task you’ll be presented with a grid of shapes in the upper
              part of the page.
            </h4>
            <h4>
              The grid will have 3 rows, and 3 columns. There will be a missing piece.
            </h4>
            <h4>
              On the bottom part of the page will be a set of 8 options. One of
              these options is the missing piece. Your job is to find this missing
              piece.
            </h4>
            <h4>
              The missing piece is the one that fits the patterns that appear across the rows and columns.
            </h4>
            <h4>
              <strong>We'll start with a practice.</strong></h4>
          </div>
          <div id="inst_3" class="inst">
            <div class="row">
              <div class="col-6">
                <h4>
                  Look at the  <span style="color: #df8343;">box</span> on the right. It has a <span style="color: #ea3767;">missing piece</span>.
                </h4>
                <h4>
                  Your task is to
                  find the missing piece from the <span style="color: #7eaa55;">options</span> at the bottom of the page.
                </h4>
                <h4>
                  Look for patterns in the <span style="color: #df8343;">box</span>. In this example, you can see that each row has a square,
                  a circle and a diamond. This is also true of each column. The missing
                  piece must therefore have a diamond shape.
                </h4>
                <h4>
                  Also, notice that the shapes on the top row have 1 dotted line through
                  them. Those in the middle row have 2 dotted lines, and in the bottom
                  row it's 3. The missing piece must have 3 dotted lines.
                </h4>
                <h4>
                  Looking at the <span style="color: #7eaa55;">options</span>, the only diamond with three dotted lines is number 5. This is the missing piece.
                </h4>
              </div>
              <div class="col-6">
                <div class="text-center shapes-test-container shapes-{{ $subtest }}">
                  <img src="/img/shapes-task/{{ $subtest }}/example_01.png" class="shapes-img" style="width: 500px!important;">
                </div>
              </div>
            </div>
          </div>
          <div id="inst_4" class="inst">
            <h4>
              After clicking the "next" button the task will begin.
            </h4>
            <h4>
              There are 14 questions and you have 7 minutes. You may not have time
              to finish all the questions, but you should work as quickly as you can.
            </h4>
            <h4>
              There will be a timer in the top right of your screen.
            </h4>
            <h4>
              You may change your answers if you change your mind, but not after the 7 minutes is up.
            </h4>
            <h4>
              Click "Next" to begin!
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
@stop
