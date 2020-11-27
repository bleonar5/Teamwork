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
      instructionPaginator(function(){ window.location = '/shapes-group';});
    });
</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      @if($subtest == 'subtest1')
        <div id="inst_1" class="inst">
          <h2 class="text-primary">Shapes Task</h2>
          <h3 class="text-success">
            Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
          </h3>
          <h4>
            This task is about understanding patterns and identifying what
            comes next. We’ll start with a practice.
          </h4>
          <h4>
            <strong>
              You only need one computer for this task.
            </strong>
          </h4>
          <h4>
            @if($isReporter)
              Click Next to see the examples.
            @else
              If you are not The Reporter, click "finish".
            @endif
          </h4>
        </div>
        <div id="inst_2" class="inst">
          <h4>
            On the top, there are four boxes.
            The last one is empty. Below you see six
            more boxes, marked a, b, c, d, e, and f. Of those six boxes,
            one will fit correctly in the empty box.
          </h4>
          <h4>
            The correct answer has been
            given to you in this first example. It’s box c.
          </h4>
          <img src="/img/shapes-task/subtest1/example_01.png" class="img-fluid">
        </div>
        <div id="inst_3" class="inst">
          <h4>
            Here's another example. The black part comes down lower and lower
            each time. So at the next step it would come more than half way down.
            The correct answer for the second example is box e.
          </h4>
          <img src="/img/shapes-task/subtest1/example_02.png" class="img-fluid">
        </div>
        <div id="inst_4" class="inst">
          <h4>
            After clicking the “next” button you will be asked to complete the
            rest yourself. As in the practices, select the box that continues
            the pattern!
          </h4>
          <h4>
            There are 13 questions and you have 3 minutes. You may not have time
            to finish all the questions, but work as quickly and carefully as you
            can. There will be a timer in the top right of your screen.
          </h4>
          <h4>
            You may change your answer if you change your mind, but not after the
            3 minutes is up.
          </h4>
        </div>

      @elseif($subtest == 'subtest2')
        <div id="inst_1" class="inst">
          <h2 class="text-primary">Shapes Task</h2>
          <h3 class="text-success">
            Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
          </h3>
          <h4>
            Last is a test of your group’s ability to recognize patterns and shapes.
          </h4>
          <h4>
            This is very similar to the shapes task you did individually. But,
            the questions are formatted differently, so we'll start with some examples.
          </h4>
          <h4>
            <strong>
              You only need one computer for this task.
            </strong>
          </h4>
          <h4>
            @if($isReporter)
              Click Next to see the examples.
            @else
              If you are not The Reporter, click "finish".
            @endif
          </h4>
        </div>
        <div id="inst_2" class="inst">
          <h3>
            Instructions and examples
          </h3>
          <h4>
            In each question you will see five boxes. Three of the boxes will be
            alike in some way. The other two are different. Your job is to find
            the two shapes that don’t fit.
          </h4>
          <h4>
            Below is an example. You can see that three of the boxes contain
            triangles (boxes a, c and e). The other two contain rectangles.
            The correct answer is <strong>b</strong> and <strong>d</strong>.
            Click on the squares below the boxes
            to select your answers.
          </h4>
          <div class="text-center shapes-test-container">
            <img src="/img/shapes-task/{{ $subtest }}/example_01.png" class="shapes-img">
            <table class="table shapes-test-table">
              <tr>
                @for($i = 1; $i < 6; $i++)
                  <td>
                    <input class="form-check-large" type="checkbox" name="{{ $i }}[]" value="{{ $i }}">
                  </td>
                @endfor
              </tr>
            </table>
          </div>
        </div>
        <div id="inst_3" class="inst">
          <h4>
            Here is another example. Two of the circles are filled-in dotted
            circles (in boxes c and e). The other three circles are empty. The
            correct answer is c and e. These are the boxes that don’t fit with
            the other three.
          </h4>
          <div class="text-center shapes-test-container">
            <img src="/img/shapes-task/subtest2/example_02.png" class="shapes-img">
            <table class="table shapes-test-table">
              <tr>
                @for($i = 1; $i < 6; $i++)
                  <td>
                    <input class="form-check-large" type="checkbox" name="{{ $i }}" value="{{ $i }}">
                  </td>
                @endfor
              </tr>
            </table>
          </div>
          <h4>
            When all three of your group members are ready, click "Next".
          </h4>
        </div>
        <div id="inst_4" class="inst">
          <h4>
            After clicking the "next" button, your group will complete the rest
            yourselves. Remember: <strong>choose two figures in each row that are
            <em>different</em> from the others</strong>.
          </h4>
          <h4>
            As always, take a moment to discuss
            how you will approach this task.
          </h4>
          <h4>
            There are 14 questions and you have 4 minutes. You may not have time
            to finish all the questions, but you should work as quickly and
            carefully as you can. There will be a timer in the top right of
            your screen.
          </h4>
          <h4>
            You may change your answers if you change your mind, but not after the 4 minutes is up.
          </h4>
          <h4>
            Click "Next" to begin!
          </h4>
        </div>
      @elseif($subtest == 'subtest3')
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Shapes Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          This is similar to the previous Shapes Tasks you’ve completed. But,
          the questions are formatted differently, so we'll start with some examples.
        </h4>
        <h4>
          <strong>
            You only need one computer for this task.
          </strong>
        </h4>
        <h4>
          @if($isReporter)
            Click Next to see the examples.
          @else
            If you are not The Reporter, click "finish".
          @endif
        </h4>
      </div>
      <div id="inst_2" class="inst">
        <h3>
          Instructions and examples
        </h3>
        <h4>
          In each question, you will see a large square with small boxes inside.
          One of the small boxes is dotted and has no drawing in it. <strong>
          Your job is to find a shape – from one of boxes below – that fits the empty
          dotted box</strong>.
        </h4>
        <h4>
          Below is a simple example. You can see that three of the boxes in the
          square have a diagonal strip (from bottom left to top right). The
          shape that best fits in the dotted box is box b.
        </h4>
        <div class="text-center shapes-test-container shapes-{{ $subtest }}">
          <img src="/img/shapes-task/{{ $subtest }}/example_01.png" class="shapes-img" style="width:800px!important">
          <table class="table shapes-test-table shapes-{{ $subtest }}" style="width:500px; top: 280px; left: 340px">
            <tr>
              @for($i = 0; $i < 6; $i++)
                <td>
                  <input class="form-check-large" type="radio" name="ex1" value="{{ $i }}">
                </td>
              @endfor
            </tr>
          </table>
        </div>
      </div>
      <div id="inst_3" class="inst">
        <h4>
          Below is a second example. First look at the top two boxes in the
          square. Going from left to right, you see that we go from two circles
          to one.
        </h4>
        <h4>
          Now look at the two boxes on the left hand side of the square. Going
          from top to bottom, you see that we go from dotted, to empty circles.
        </h4>
        <h4>
          Repeating these patterns would mean that the dotted box would have a
          single, empty circle. So the answer is box f.
        </h4>
        <div class="text-center shapes-test-container shapes-{{ $subtest }}">
          <img src="/img/shapes-task/{{ $subtest }}/example_02.png" class="shapes-img" style="width:800px!important">
          <table class="table shapes-test-table shapes-{{ $subtest }}" style="width:500px; top: 280px; left: 340px">
            <tr>
              @for($i = 0; $i < 6; $i++)
                <td>
                  <input class="form-check-large" type="radio" name="ex2" value="{{ $i }}">
                </td>
              @endfor
            </tr>
          </table>
        </div>
        <h4>
          When all three of your group members are ready, click "Next".
        </h4>
      </div>
      <div id="inst_4" class="inst">
        <h4>
          After clicking the "next" button, your group will complete the rest
          yourselves.
        </h4>
        <h4>
          As always, take a moment to discuss
          how you will approach this task.
        </h4>
        <h4>
          There are 13 questions and you have 3 minutes. You may not have time
          to finish all the questions, but you should work as quickly and
          carefully as you can. There will be a timer in the top right of
          your screen.
        </h4>
        <h4>
          You may change your answers if you change your mind, but not after the 3 minutes is up.
        </h4>
        <h4>
          Click "Next" to begin!
        </h4>
      </div>
      @elseif($subtest == 'subtest4')
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Shapes Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          This is similar to the previous Shapes Tasks you’ve completed. But,
          the questions are formatted differently, so we'll start with some examples.
        </h4>
        <h4>
          <strong>
            You only need one computer for this task.
          </strong>
        </h4>
        <h4>
          @if($isReporter)
            Click Next to see the examples.
          @else
            If you are not The Reporter, click "finish".
          @endif
        </h4>
      </div>
      <div id="inst_2" class="inst">
        <h3>
          Instructions and examples
        </h3>
        <h4>
          There is a separate box at the top with some shapes and
          a dot. The dot is important. Below this "top box" are 5 possible
          "matches", marked a, b, c, d and e.
          One of these 5 boxes will be a "match". For a box to be a match,
          you must be able to put the dot in the same position as it is
          in the "top box".
        </h4>
        <h4>
          Below is an example. In the top box, the dot is
          <em>inside both</em> the square and the circle. The only answer where this
          is a possibility is box c. To make this obvious, we have included
          the dot in box c.
        </h4>
        <div class="text-center shapes-test-container shapes-{{ $subtest }}">
          <img src="/img/shapes-task/{{ $subtest }}/example_01.png" class="shapes-img shapes-{{ $subtest }}">
          <table class="table shapes-test-table shapes-{{ $subtest }}">
            <tr>
              @for($i = 0; $i < 5; $i++)
                <td>
                  <input class="form-check-large" type="radio" name="ex1" value="{{ $i }}">
                </td>
              @endfor
            </tr>
          </table>
        </div>
      </div>
      <div id="inst_3" class="inst">
        <h4>
          Here is another example. In the top box, you can see that the dot is
          inside the triangle but <strong>not</strong> inside the rectangle.
        </h4>
        <h4>
          Among the 5 potential answers, the only box where we could place a
          dot inside a triangle, but outside a rectangle is box d. Box d is
          the answer.
        </h4>
        <div class="text-center shapes-test-container shapes-{{ $subtest }}">
          <img src="/img/shapes-task/{{ $subtest }}/example_02.png" class="shapes-img">
          <table class="table shapes-test-table shapes-{{ $subtest }}">
            <tr>
              @for($i = 0; $i < 5; $i++)
                <td>
                  <input class="form-check-large" type="radio" name="ex2" value="{{ $i }}">
                </td>
              @endfor
            </tr>
          </table>
        </div>
        <h4>
          When all three of your group members are ready, click "Next" to see a
          final example.
        </h4>
      </div>
      <div id="inst_4" class="inst">
        <h4>
          One final example. In the top box, notice that the dot is above the
          curved line, and inside the triangle. The only box where it is
          possible to place a dot in this situation is box b.
        </h4>
        <div class="text-center shapes-test-container shapes-{{ $subtest }}">
          <img src="/img/shapes-task/{{ $subtest }}/example_03.png" class="shapes-img">
          <table class="table shapes-test-table shapes-{{ $subtest }}">
            <tr>
              @for($i = 0; $i < 5; $i++)
                <td>
                  <input class="form-check-large" type="radio" name="ex2" value="{{ $i }}">
                </td>
              @endfor
            </tr>
          </table>
        </div>
        <h4>
          When all three of your group members are ready, click "Next".
        </h4>
      </div>
      <div id="inst_5" class="inst">
        <h4>
          After clicking the "next" button your group will be asked to complete
          the rest yourselves. As in the practices, find the box that "matches"
          the top box.
        </h4>
        <h4>
          There are 10 questions and you have 3 minutes. You may not have time
          to finish all the questions, but you should work as quickly and
          carefully as you can. There will be a timer in the top right of
          your screen.
        </h4>
        <h4>
          <strong>
            As always, take a moment to discuss how
            you will approach this task.
          </strong>
        </h4>
        <h4>
          You may change your answers if you change your mind, but not after the 3 minutes is up.
        </h4>
        <h4>
          Click "Next" to begin!
        </h4>
      </div>
      @elseif($subtest == 'subtest5')
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Shapes Task</h2>
        <h3 class="text-success">
          Task {{ \Session::get('completedTasks') + 1 }} of {{ \Session::get('totalTasks') }}
        </h3>
        <h4>
          This task is similar to the shapes task you did in your previous group.
          Broadly, the aim of the task is to understand shapes and patterns.
        </h4>
        <h4>
          <strong>
            Once again, you will answer as a group. Everyone should be gathered
            around <em>The Reporter's</em> laptop.
          </strong>
        </h4>
        <h4>
          @if($isReporter)
            Click Next to continue.
          @else
            If you are not The Reporter, you can click "finish" and close your laptop:
            you won’t be needing it again.
          @endif
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
              Look at the picture on the top right. The missing piece is in the bottom right corner.
            </h4>
            <h4>
              There are several patterns here. You can see that each row has a square,
              a circle and a diamond. This is also true of each column. The missing
              piece must therefore have a diamond shape.
            </h4>
            <h4>
              Also, notice that the shapes on the top row have 1 dotted line through
              them. Those in the middle row have 2 dotted lines, and in the bottom
              row it's 3. The missing piece must have 3 dotted lines.
            </h4>
            <h4>
              Looking at the options, the only diamond with three dotted lines is number 5. This is the missing piece.
            </h4>
          </div>
          <div class="col-6">
            <div class="text-center shapes-test-container shapes-{{ $subtest }}">
              <img src="/img/shapes-task/{{ $subtest }}/example_01.png" class="shapes-img">
              <div class="form-group justify-content-center">
                <label for="example_input">
                  Select the number of the missing piece:
                </label><br>
                <select class="form-control form-control-lg" style="width: 64px; display: inline-block; margin: 0 auto;" name="example_input">
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
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="inst_4" class="inst">
        <h4>
          After clicking the "next" button your group will be asked to complete the rest yourselves.
        </h4>
        <h4>
          There are 14 questions and you have 7 minutes. You may not have time
          to finish all the questions, but you should work as quickly as you can.
        </h4>
        <h4>
          Remember, you're working as a team and will only submit one set of answers.
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
      @if($isReporter)
        <div id="instr_nav" class="text-center">
          <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
          <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;"><br />
        </div>
      @else
        <div class="text-center">
          <a href="/end-shapes-task" class="btn btn-primary btn-lg" role="button" name="finish" id="finish" >Finish</a><br />
        </div>
      @endif
    </div>
  </div>
</div>
@stop
