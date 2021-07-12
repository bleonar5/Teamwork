@extends('layouts.master')

@section('js')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>

@stop


@section('content')
<script>
$( document ).ready(function() {
    var canvas = document.querySelector("canvas");
    canvas.width  = $(canvas).parent().width();
    var signaturePad = new SignaturePad(canvas);
    $(window).on("resize", function() {
      canvas.width  = $(canvas).parent().width();
    });

    $('#consent_button').on('click',function(event){
      //console.log(signaturePad.toDataURL());
      //console.log('turds');
      event.preventDefault();
      if(signaturePad.isEmpty()) {
        alert('Please use the mouse to draw your signature in the box');
        
        return;
      }
      else{
        console.log(signaturePad.toDataURL());
        $.post('/submit-consent',data={
          _token: '{{ csrf_token() }}',
          signature: signaturePad.toDataURL(),
        },success=function(data){
          console.log(data);
          window.location.href = '/{{ $url_endpoint }}';

        }).fail(function(){
          console.log('failure');
        });
      }
    })
  });

</script>
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
            <span class="consent-em">WHAT YOU’LL DO</span>: You will be asked to solve puzzles, perform memory tasks, and identify people’s emotions based on photographs. 
          </p>
          <p>
            <span class="consent-em">RISKS</span>: There are no risks
            for participating in this study beyond those associated with normal
            computer use, including fatigue and mild stress.
          </p>
          <p>
            <span class="consent-em">COMPENSATION</span>: Average payment will be $15 per person. Payment depends on task performance. Minimum payment is $10.
          </p>
          <p>
            <span class="consent-em">PLEASE NOTE</span>: This study contains a number of checks to make sure participants are finishing the tasks <mark>honestly and completely</mark>.
          </p>
          <p>
            <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
              WITHDRAW</span>: Participation in this study is voluntary, and
              you can stop at any time without any penalty.  To stop, simply
              close your browser window. Partial data will not be analyzed.
          </p>
          <p>
            <span class="consent-em">CONFIDENTIALITY</span>:  All data collected as part of this study will be anonymized and used only for academic research purposes.
          </p>
          <p>
            <span class="consent-em">CONTACT INFORMATION</span>: If you have any
            questions about this research, you may contact: <a href='mailto:skillslab@hks.harvard.edu'>skillslab@hks.harvard.edu</a>
          </p>
          <p>
            <span class="consent-em">CLICKING ACCEPT</span>: By signing and clicking on the
            "I Consent" button, you indicate that you are 18 years of age or older,
            that you voluntarily agree to participate in this study and that you
            understand the information in this consent form.
          </p>
          <p>
            <span class="consent-em">SIGNATURE</span>: 
          </p>
          <div class="form-group row">
              <div class="col-12">
                <canvas style='border:1px solid black' class="img-responsive" width="100%" height="auto"></canvas>
              </div>
          </div>
        @elseif($subjectPool == 'hdsl_individual')
          <p>
            <span class="consent-em">PURPOSE OF RESEARCH STUDY</span>: To understand
            the relationship between measures of social skill and fluid intelligence.
          </p>
          <p>
            <span class="consent-em">WHAT YOU’LL DO</span>This is a pilot study. You will work together in a group of 3 people to solve a cryptography task, then fill in a feedback form. The study will last 25 minutes.
          </p>
          <p>
            <span class="consent-em">RISKS</span>: There are no risks
            for participating in this study beyond those associated with normal
            computer use, including fatigue and mild stress.
          </p>
          <p>
            <span class="consent-em">COMPENSATION</span>:You will be paid $6.25
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
            questions about this research, you may contact: <a href='mailto:skillslab@hks.harvard.edu'>skillslab@hks.harvard.edu</a>
          </p>
          <p>
            <span class="consent-em">CLICKING ACCEPT</span>: By signing and clicking on the
            "I Consent" button, you indicate that you are 18 years of age or older,
            that you voluntarily agree to participate in this study and that you
            understand the information in this consent form.
          </p>
          <p>
            <span class="consent-em">SIGNATURE</span>: 
          </p>
          <div class="form-group row">
              <div class="col-12">
                <canvas style='border:1px solid black' class="img-responsive" width="100%" height="auto"></canvas>
              </div>
          </div>

        @elseif($subjectPool == 'july_pilot')
          <p>
            <span class="consent-em">PURPOSE OF RESEARCH STUDY</span>: To pilot an experiment examining teamwork skills.
          </p>
          <p>
            <span class="consent-em">WHAT YOU’LL DO</span>: You will be asked to solve cryptography puzzles individually and in a group. You will also be asked to provide feedback about the experiment.
          </p>
          <p>
            <span class="consent-em">RISKS</span>: There are no risks
            for participating in this study beyond those associated with normal
            computer use, including fatigue and mild stress.
          </p>
          <p>
            <span class="consent-em">COMPENSATION</span>:This is a 2 part study. <b> Payment for part 1 is $15</b> (total payment, for both parts, averages $50).
          </p>
          <p>
            <span class="consent-em">VOLUNTARY PARTICIPATION AND RIGHT TO
              WITHDRAW</span>: Participation in this study is voluntary, and
              you can stop at any time without any penalty.  To stop, simply
              close your browser window. Partial data will not be analyzed.
          </p>
          <p>
            <span class="consent-em">CONFIDENTIALITY</span>:  All data collected as part of this study will be destroyed at the end of the pilot and only used for academic purposes.
          </p>
          <p>
            <span class="consent-em">CONTACT INFORMATION</span>: If you have any
            questions about this research, you may contact: <a href='mailto:skillslab@hks.harvard.edu'>skillslab@hks.harvard.edu</a>
          </p>
          <p>
            <span class="consent-em">CLICKING ACCEPT</span>: By signing and clicking on the
            "I Consent" button, you indicate that you are 18 years of age or older,
            that you voluntarily agree to participate in this study and that you
            understand the information in this consent form.
          </p>
          <p>
            <span class="consent-em">SIGNATURE</span>: 
          </p>
          <div class="form-group row">
              <div class="col-12">
                <canvas style='border:1px solid black' class="img-responsive" width="100%" height="auto"></canvas>
              </div>
          </div>




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
      <a id='consent_button' href="/{{ $url_endpoint }}" role="button" class="btn btn-lg btn-success float-right">I Consent</a>
    </div>
  </div>
</div>
@stop
