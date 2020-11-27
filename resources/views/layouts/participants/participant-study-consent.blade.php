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
          tasks honestly and completely.  As long as you read the instructions
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
            <span class="consent-em">WHAT YOU’LL DO</span>: You will be asked to
            solve puzzles, perform memory tasks, and identify people’s emotions
            based on photographs. The online tasks (which you can do at home) should
            take around 50 minutes. Then participants will come into the lab TWICE. The first
            session will take less than an hour. The second session will take around
            80 minutes. Both times you'll be working on puzzles and problems with a small
            group of people.
          </p>
          <p>
            <span class="consent-em">RISKS</span>: There are no risks
            for participating in this study beyond those associated with normal
            computer use, including fatigue and mild stress.
          </p>
          <p>
            <span class="consent-em">COMPENSATION</span>: Overall, compensation for this
            experiment will be $90. This requires participants to complete these online
            tasks and to come to <strong>two</strong> lab sessions.
          </p>
          <p>
            <span class="consent-em">PLEASE NOTE</span>: This study contains a
            number of checks to make sure that participants are finishing the
            tasks honestly and completely. Also, please be aware that we will be
            recording lab sessions, and analyzing group communication.
          </p>
          <p>
            <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
              WITHDRAW</span>: Participation in this study is voluntary, and
              you can stop at any time without any penalty.  To stop, simply
              close your browser window. Partial data will not be analyzed.
          </p>
          <p>
            <span class="consent-em">CONFIDENTIALITY</span>: All data collected as part
            of this study will be anonymized (and personal information, such as your
            email address, will be removed).
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
              <span class="consent-em">WHAT YOU’LL DO</span>: You will be asked to
              solve puzzles, perform memory tasks, and identify people’s emotions
              based on photographs. The online tasks (which you can do at home) should
              take around 50 minutes. Then participants will come into the lab and
              solve similar problems in groups. This will take around 80 minutes.
            </p>
            <p>
              <span class="consent-em">RISKS</span>: There are no risks
              for participating in this study beyond those associated with normal
              computer use, including fatigue and mild stress.
            </p>
            <p>
              <span class="consent-em">COMPENSATION</span>: Overall, compensation for this
              pilot will be $35.
            <p>
              <span class="consent-em">PLEASE NOTE</span>: This study contains a
              number of checks to make sure that participants are finishing the
              tasks honestly and completely.
            </p>
            <p>
              <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
                WITHDRAW</span>: Participation in this study is voluntary, and
                you can stop at any time without any penalty.  To stop, simply
                close your browser window. Partial data will not be analyzed.
            </p>
            <p>
              <span class="consent-em">CONFIDENTIALITY</span>: All data collected as part
              of this study will be anonymized (and personal information, such as your
              email address, will be removed).
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
