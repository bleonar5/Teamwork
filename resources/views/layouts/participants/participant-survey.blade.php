@extends('layouts.master')


@section('content')
<script>
  $(document).ready(function() {
    $(".alert").hide();
    $("#submit").on("click", function(event) {
      $(".alert").hide();
      radioNames = [];
      $("input:radio").each(function (i, v){
        if($.inArray($(this).attr('name'), radioNames) == -1) radioNames.push($(this).attr('name'));
      });

      validated = true;
      $.each(radioNames, function(i, val) {
          if(!$("input:radio[name='"+val+"']").is(":checked")){
            validated = false;
          }
      });

      if(!validated){
        $(".alert").show();
        event.preventDefault();
      }
    });
  });
</script>
<div class="container">
  <div class="row vertical-center">
    <div class="col-md-6 offset-3 text-left">
      @if($survey == 'hdsl')
        <form action="/survey" method="post" class="survey-form">
          {{ csrf_field() }}
          <h4>
            Please tell us a bit about yourself...
          </h4>
          <div style="margin-left: 64px;" class="form-div">
            <div class="form-group">
              <h5>What is your gender</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="male">
                <label class="form-check-label" for="gender">
                  Male
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="female">
                <label class="form-check-label" for="gender">
                  Female
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="other">
                <label class="form-check-label" for="gender">
                  Other
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="decline">
                <label class="form-check-label" for="gender">
                  I decline to answer
                </label>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <h5>What is the highest level of education you have completed (or are currently attending):</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="some_highscool">
                <label class="form-check-label" for="education">
                  Some high school
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="diploma_ged">
                <label class="form-check-label" for="education">
                  High school diploma or GED
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="some_college">
                <label class="form-check-label" for="education">
                  Some college
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="associate_degree">
                <label class="form-check-label" for="education">
                  Associate Degree
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="bachelors_degree">
                <label class="form-check-label" for="education">
                  Bachelors Degree
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="some_grad_school">
                <label class="form-check-label" for="education">
                  Some graduate school
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="grad_degree">
                <label class="form-check-label" for="education">
                  Graduate or professional degree
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="education" value="decline">
                <label class="form-check-label" for="education">
                  I decline to answer
                </label>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <h5>You identify as:</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="african_american">
                <label class="form-check-label" for="ethnicity">
                  African American (non-Hispanic)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="asian">
                <label class="form-check-label" for="ethnicity">
                  Asian / Pacific Islander
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="caucasian">
                <label class="form-check-label" for="ethnicity">
                  Caucasian (non-Hispanic)
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="latino_hispanic">
                <label class="form-check-label" for="ethnicity">
                  Latino or Hispanic
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="native_american">
                <label class="form-check-label" for="ethnicity">
                  Native American, Aleut, or Aboriginal Peoples
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="mixed">
                <label class="form-check-label" for="ethnicity">
                  Mixed Race
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="other">
                <label class="form-check-label" for="ethnicity">
                  Other
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="ethnicity" value="decline">
                <label class="form-check-label" for="ethnicity">
                  I decline to answer
                </label>
              </div>
            </div>
            <hr>
            <div class="form-group">
              <h5>What is the year of your birth?</h5>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1950-earlier">
                <label class="form-check-label" for="birth_year">
                  1950 or earlier
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1951-1960">
                <label class="form-check-label" for="birth_year">
                  1951 - 1960
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1961-1965">
                <label class="form-check-label" for="birth_year">
                  1961 - 1965
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1966-1970">
                <label class="form-check-label" for="birth_year">
                  1966 - 1970
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1971-1975">
                <label class="form-check-label" for="birth_year">
                  1971 - 1975
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1976-1980">
                <label class="form-check-label" for="birth_year">
                  1976 - 1980
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1981-1985">
                <label class="form-check-label" for="birth_year">
                  1981 - 1985
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1986-1990">
                <label class="form-check-label" for="birth_year">
                  1986 - 1990
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1991-1994">
                <label class="form-check-label" for="birth_year">
                  1991 - 1994
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1995-1997">
                <label class="form-check-label" for="birth_year">
                  1995 - 1997
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="1998-2002">
                <label class="form-check-label" for="birth_year">
                  1998 - 2002
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="birth_year" value="decline">
                <label class="form-check-label" for="birth_year">
                  I decline to answer
                </label>
              </div>
            </div>
            <div class="alert alert-danger" role="alert">Please answer all questions before continuing</div>
          </div>
          <div class="text-center">
            <button class="btn btn-lg btn-primary" id="submit" type="submit">Next</button>
          </div>
        </form>
      @endif
    </div>
  </div>
</div>
@stop
