@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/instructionPaginator.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')
<script>
var timeout;
$( document ).ready(function() {
  timeout = setTimeout(function() {
    window.location = '/cryptography-individual';
  },1000 * 180);
  instructionPaginator(function(){clearTimeout(timeout); window.location = '/cryptography-individual';});
});

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <div id="inst_1" class="inst">
        <h2 class="text-primary">Cryptography Task - Second Attempt</h2>
        <h4>
          You now have an opportunity to try the Cryptography Task for a second and final time. The task is the same, but the letter-number combinations have changed.
        </h4>
        <h4>
          Each of the letters from A to J has a numerical value (from 0 to 9)
          and your job is to find out the value of each letter.
        </h4>
        <h4>
          Remember that the goal is to <strong>solve the puzzle in the SMALLEST number of trials</strong>
        </h4>
        <h4>
          One participant who on this second puzzle is in the top third of all participants will be randomly selected to win a prize of <strong>$50</strong>.
        </h4>
        <h4>
          On the next page, we will give a quick refresher of the instructions.<br>
        </h4>
      </div>
      <div id="inst_2" class="inst">
        <h2 class="text-primary">
          To review:
        </h2>
        <h4>
          You will have a maximum of {{ $maxResponses }} trials and 10 minutes to solve
          the cryptography task. Each trial has three elements:
        </h4>
        <div class="row">
          <div class="col-md-8 offset-md-2 text-left">
            <h4>
              1. <span class="text-equation">Enter an equation</span> (e.g. CC + B - A = ?)<br>
              2. <span class="text-hypothesis">Make a hypothesis</span> (e.g. C = 1)<br>
              3. <span class="text-guess">Guess the letter values</span>
            </h4>
          </div>
        </div>
        <h4>
          The overall goal is to solve the whole puzzle in the minimum number
          of trials.<br>
          If you don’t solve the task, <strong>you will get some points for
          each letter-number combination you correctly identify</strong>.
        </h4>
        <h4>
          You will have a maximum of {{ $maxResponses }} trials and 10 minutes to
          solve the cryptography task.<br>
          No calculators are allowed.<br>
          When you press "Next" your 10 minutes will begin!
        </h4>
      </div>
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;"><br />
      </div>
    </div>
  </div>
</div>
@stop
