@extends('layouts.master')


@section('content')
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-left">
      <h2 class="text-center">
        Informed Consent Form
      </h2>
      @if($subjectPool == 'mturk')
        <p>
          <span class="consent-em">PURPOSE OF RESEARCH STUDY</span>: To understand
          the relationship between measures of social skill and fluid intelligence.
        </p>
        <p>
          <span class="consent-em">WHAT YOU’LL DO</span>: You will be asked to
          solve puzzles, perform memory tasks, and identify people’s emotions
          based on photographs. In total, we expect the tasks to take 60-70
          minutes. There are 7 tasks in total. You should feel free to take a
          break between tasks.
        </p>
        <p>
          <span class="consent-em">RISKS</span>: There are no risks
          for participating in this study beyond those associated with normal
          computer use, including fatigue and mild stress.
        </p>
        <p>
          <span class="consent-em">COMPENSATION</span>: If you read the
          instructions and satisfactorily complete all the tasks <strong>
          you will receive 8 USD</strong>. MTurk does not allow for prorated
          compensation. In the event of an incomplete HIT, you must contact
          the research team (see contact information below) and compensation will be
          determined based on what was completed and at the researchers'
          discretion.
        </p>
        <p>
          <span class="consent-em">PLEASE NOTE</span>: This study contains a
          number of checks to make sure that participants are finishing the
          tasks <span style = "background-color: yellow">honestly and completely.</span> As long as you read the instructions
          and complete the tasks, your HIT will be definitely be approved!
        </p>
        <p>
          <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
            WITHDRAW</span>: Participation in this study is voluntary, and
            you can stop at any time without any penalty.  To stop, simply
            close your browser window. Partial data will not be analyzed.
        </p>
        <p>
          <span class="consent-em">CONFIDENTIALITY</span>: Your Mechanical
          Turk Worker ID will be used to distribute payment to you but will not
          be stored with the research data we collect from you.  Please be aware
          that your MTurk Worker ID can potentially be linked to information
          about you on your Amazon public profile page, depending on the settings
          you have for your Amazon profile.  We will not be accessing any
          personally identifying information about you that you may have put
          on your Amazon public profile page.
        </p>
        <p>
          <span class="consent-em">CONTACT INFORMATION</span>: If you have any
          questions about this research, you may contact: Ben Weidmann at
          <a href="mailto:benweidmann@g.harvard.edu">benweidmann@g.harvard.edu</a>,
          or David Deming at <a href="mailto:david_deming@harvard.edu">david_deming@harvard.edu</a>
        </p>
        <p>
          <span class="consent-em">CLICKING ACCEPT</span>: By clicking on the
          "I Consent" button, you indicate that you are 18 years of age or older,
          that you voluntarily agree to participate in this study and that you
          understand the information in this consent form.
        </p>
        @elseif($subjectPool == 'hdsl_individual')
          <p>
            <span class="consent-em">PURPOSE OF RESEARCH STUDY</span>: To understand
            the relationship between measures of social skill and fluid intelligence.
          </p>
          <p>
            <span class="consent-em">WHAT YOU’LL DO</span>:You will be asked to solve puzzles, perform memory tasks, and identify people’s emotions based on photographs. <strong>The entire experiment will be completed online.</strong> The first set of tasks are completed individually and take around 60 minutes. You will then be asked to sign up for sessions where you solve puzzles in a group of 3 people. There will be a video link between you and 2 other people. Each of these sessions will last around 60 minutes.
          </p>
          <p>
            <span class="consent-em">RISKS</span>: There are no risks
            for participating in this study beyond those associated with normal
            computer use, including fatigue and mild stress.
          </p>
          <p>
            <span class="consent-em">COMPENSATION</span>: OAverage payment will be $X per person (with a minimum of $Y). Payment will depend in part on how well your groups perform.
          </p>
          <p>
            <span class="consent-em">PLEASE NOTE</span>: This study contains a number of checks to make sure that participants are finishing the tasks <span style = "background-color: yellow">honestly and completely.</span>
          </p>
          <p>
            <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
              WITHDRAW</span>: Participation in this study is voluntary, and
              you can stop at any time without any penalty.  To stop, simply
              close your browser window. Partial data will not be analyzed.
          </p>
          <p>
            <span class="consent-em">CONFIDENTIALITY</span>:  All data collected as part of this study will be anonymized (and personal information, such as your email address, will be deleted at the conclusion of the study). Please note that we will be recording and analyzing the video interactions between you and your group. These videos will be permanently deleted at the conclusion of the project.
          </p>
          <p>
            <span class="consent-em">CONTACT INFORMATION</span>: If you have any
            questions about this research, you may contact: Ben Weidmann at
            <a href="mailto:benweidmann@g.harvard.edu">benweidmann@g.harvard.edu</a>,
            or David Deming at <a href="mailto:david_deming@harvard.edu">david_deming@harvard.edu</a>
          </p>
          <p>
            <span class="consent-em">CLICKING ACCEPT</span>: By clicking on the
            "I Consent" button, you indicate that you are 18 years of age or older,
            that you voluntarily agree to participate in this study and that you
            understand the information in this consent form.
          </p>

          @elseif($subjectPool == 'hdsl_individual_pilot')
            <p>
              <span class="consent-em">PURPOSE OF RESEARCH STUDY</span>: To understand
              the relationship between measures of social skill and fluid intelligence.
            </p>
            <p>
              <span class="consent-em">WHAT YOU’LL DO</span>: You will be asked to solve puzzles, perform memory tasks, and identify people’s emotions based on photographs. <strong>The entire experiment will be completed online.</strong> The first set of tasks are completed individually and take around 60 minutes. You will then be asked to sign up for sessions where you solve puzzles in a group of 3 people. There will be a video link between you and 2 other people. Each of these sessions will last around 60 minutes.
            </p>
            <p>
              <span class="consent-em">RISKS</span>: There are no risks
              for participating in this study beyond those associated with normal
              computer use, including fatigue and mild stress.
            </p>
            <p>
              <span class="consent-em">COMPENSATION</span>: Average payment will be $X per person (with a minimum of $Y). Payment will depend in part on how well your groups perform.
            <p>
              <span class="consent-em">PLEASE NOTE</span>: This study contains a
              number of checks to make sure that participants are finishing the
              tasks <span style = "background-color: yellow">honestly and completely.</span>
            </p>
            <p>
              <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
                WITHDRAW</span>: Participation in this study is voluntary, and
                you can stop at any time without any penalty.  To stop, simply
                close your browser window. Partial data will not be analyzed.
            </p>
            <p>
              <span class="consent-em">CONFIDENTIALITY</span>: All data collected as part of this study will be anonymized (and personal information, such as your email address, will be deleted at the conclusion of the study). Please note that we will be recording and analyzing the video interactions between you and your group. These videos will be permanently deleted at the conclusion of the project.
            </p>
            <p>
              <span class="consent-em">CONTACT INFORMATION</span>: If you have any
              questions about this research, you may contact: Ben Weidmann at
              <a href="mailto:benweidmann@g.harvard.edu">benweidmann@g.harvard.edu</a>,
              or David Deming at <a href="mailto:david_deming@harvard.edu">david_deming@harvard.edu</a>
            </p>
            <p>
              <span class="consent-em">CLICKING ACCEPT</span>: By clicking on the
              "I Consent" button, you indicate that you are 18 years of age or older,
              that you voluntarily agree to participate in this study and that you
              understand the information in this consent form.
            </p>
      @endif
      <a href="/no-study-consent" role="button" class="btn btn-lg btn-warning float-left">I Do Not Consent</a>
      <a href="/end-individual-task" role="button" class="btn btn-lg btn-success float-right">I Consent</a>
    </div>
  </div>
</div>
@stop