@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">

      @foreach($conclusionContent as $content)
        @if($content['type'] == 'header')
          <h2 class="text-primary">{!! $content['content'] !!}</h2>
        @elseif($content['type'] == 'sub-header')
          <h4>{!! $content['content'] !!}</h4>
        @elseif($content['type'] == 'paragraph')
          <h4>{!! $content['content'] !!}</h4>
        @endif
      @endforeach

      @if($code)
        <h4>Your participation code is:<br>
          <span class="text-success">{{ $code }}</span>
        </h4>
        <h4>
          Nobody else has this unique code. Please enter the code back in MTurk so
          that we can verify you completed the tasks and we can pay you.
        </h4>
        <h4>
          This concludes the study. Thanks again!
        </h4>
      @endif

      @if($score)
        <h4>
          For randomization purposes, we have allocated you to a group.<br>
          You are in the <strong>{{ strtoupper($score) }}</strong> group.
        </h4>
        <div class="row">
          <div class="col-4 offset-4">
            <img src="/img/fruits/{{ $score }}.png" class="img-fluid" style="width:50%;">
          </div>
        </div>
        <h4>
          When you come to the lab we will ask you about your fruit, so please try to remember it.
        </h4>
      @endif

      @if($checkEligibility)
        @if($eligible)
          <h2>Congratulations on completing the tasks!</h2>
          <h4>You are now eligible for the <span class="text-primary">Superteams Study</span>.</h4><br>
          <h4>
            You need to come to the lab twice to complete the study.
          </h4><br>
          <h4>
            Superteams Lab Session 1 will take 50-60 minutes. You will be paid $30 in total
            [$20 for the visit to the lab, and $10 for your time in completing these online tasks].
          </h4><br>
          <h4>
            Superteams Lab Session 2 will take 80-90 minutes. You will be paid $60 for your time. Total payment for participating in the study will be $90.
          </h4><br>
          <h4 class="danger">
            You can sign up for Lab Session 1 now
          </h4><br>
          <h4>
            If you have any questions or concerns, please contact <a href="mailto:benweidmann@g.harvard.edu">benweidmann@g.harvard.edu</a>.
          </h4><br>
          <h4>
            We look forward to seeing you at the lab!
          </h4>
        @else
          <h2>Eligibility Notice</h2>
          <h4>
            Based on your responses, unfortunately you are not yet eligible to participate in the Superteams Study.
          </h4>
          <h4>
            As noted at the start of these tasks, we included several checks to ensure
            that participants were reading instructions and questions. These checks do
            <strong>not</strong> focus on performance. They are
            designed to make sure participants have paid attention to the questions
            and attempted to answer them correctly. This is essential for the Superteams Study.
          </h4>
          <h4>
            You are welcome to re-attempt these tasks by clicking the button below.
          </h4>
          <h4>
            If you have any questions or concerns, please contact <a href="mailto:benweidmann@g.harvard.edu">benweidmann@g.harvard.edu</a>.
          </h4>
            <a class="btn btn-lg btn-success" href="/retry-individual-tasks">Retry</a>
        @endif

      @endif

      @if($feedbackLink)
        <h4>
          Click the button below to add any feedback or comments on this study. Even a short sentance is very helpful to us!
        </h4>
        <a class="btn btn-lg btn-success" href="{{ $feedbackLink }}">Feedback</a>
      @endif


      @if($receiptSonaId)
        <a class="btn btn-lg btn-success" href="http://dashboard.harvarddecisionlab.org/new-receipt/?i={{ base64_encode($receiptSonaId) }}&p={{ base64_encode($payment) }}">Sign Your Digital Receipt</a>
      @endif
    </div>
  </div>
</div>
@stop
