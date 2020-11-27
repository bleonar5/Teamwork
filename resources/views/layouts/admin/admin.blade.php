@extends('layouts.master')

@section('content')
<script>

  var individualFilter = 'week';
  var groupFilter = 'week';

  $(document).ready(function() {
    $("#individual-date").hide();
    $("#group-date").hide();

    // Individual responses
    $("input[name='individual_filter_responses']").change(function(){
      let filterVal = $(this).val();
      individualFilter = filterVal;
      if(filterVal == 'date') {
        $("#individual-date").show(300);
        individualFilter += '&date=' + $("#individual-filter-date").val();
      }

      else {
        $("#individual-date").hide(300);
      }

    });

    $("#individual-filter-date").change(function() {
      individualFilter = 'date&date=' + $("#individual-filter-date").val();
    });

    $("#individual-data").click(function(event) {
      console.log("/download-individual-csv?filter=" + individualFilter);
      window.location = "/download-individual-csv?filter=" + individualFilter;
      event.preventDefault();
    });

    // Group responses
    $("input[name='group_filter_responses']").change(function(){
      let groupFilterVal = $(this).val();
      groupFilter = groupFilterVal;
      if(groupFilterVal == 'date') {
        $("#group-date").show(300);
        groupFilter += '&date=' + $("#group-filter-date").val();
      }

      else {
        $("#group-date").hide(300);
      }

    });

    $("#group-filter-date").change(function() {
      groupFilter = 'date&date=' + $("#group-filter-date").val();
    });

    $("#group-data").click(function(event) {
      console.log(groupFilter);
      window.location = "/download-group-csv?filter=" + groupFilter;
      event.preventDefault();
    });

  });
</script>
<div class="container">
    <div class="row">
      <div class="col-md-6">
        <div class="card mb-4">
          <h5 class="card-header">Individual Responses</h5>
          <div class="card-body">
            <em class="text-secondary">
              Filter by
            </em>
            <form class="p-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" class="individual-filter" name="individual_filter_responses" value="day">
                <label class="form-check-label" for="individual_filter_responses">
                  Today
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" class="individual-filter" name="individual_filter_responses" value="week" checked>
                <label class="form-check-label" for="individual_filter_responses">
                  Current Week
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" class="individual-filter" name="individual_filter_responses" value="all">
                <label class="form-check-label" for="individual_filter_responses">
                  All responses
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" class="individual-filter" name="individual_filter_responses" value="date">
                <label class="form-check-label" for="individual_filter_responses">
                  By date
                </label>
              </div>
              <div id="individual-date" class="mb-2">
                <input class="form-control" type="date" id="individual-filter-date" name="individual_filter_date"
                   value="{{ date('Y-m-d') }}"
                   min="2019-01-01" max="{{ date('Y-m-d') }}">
              </div>
              <a href="#" class="btn btn-success" id="individual-data">Download CSV</a>
            </form>
          </div>
        </div>

        <div class="card">
          <h5 class="card-header">Group Responses</h5>
          <div class="card-body">
            <em class="text-secondary">
              Filter by
            </em>
            <form class="p-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" class="group-filter" name="group_filter_responses" value="day">
                <label class="form-check-label" for="group_filter_responses">
                  Today
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" class="group-filter" name="group_filter_responses" value="week" checked>
                <label class="form-check-label" for="group_filter_responses">
                  Current Week
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" class="group-filter" name="group_filter_responses" value="all">
                <label class="form-check-label" for="group_filter_responses">
                  All responses
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" class="group-filter" name="group_filter_responses" value="date">
                <label class="form-check-label" for="group_filter_responses">
                  By date
                </label>
              </div>
              <div id="group-date" class="mb-2">
                <input class="form-control" type="date" id="individual-filter-date" name="group_filter_date"
                   value="{{ date('Y-m-d') }}"
                   min="2019-01-01" max="{{ date('Y-m-d') }}">
              </div>
              <a href="#" class="btn btn-success" id="group-data">Download CSV</a>
            </form>
          </div>
        </div>
        <a class="btn btn-warning mt-4 ml-4"href="\admin\users" role="button">
          View Users
        </a>
      </div>

      <div class="col-md-6">
        <h4>Lab Session 1 Timeslots</h4>
        @foreach($sessionOneSlots as $key => $slotsOne)

          <div class="dropdown show mb-4">
            <a class="btn btn-info dropdown-toggle" href="#" role="button"
               style="width: 400px;"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ date('D M j \a\t g:i', strtotime($slotsOne['datetime'])) }}
              ::
              {{ $slotsOne['numSignedUp'] }} / {{ $slotsOne['numRequested'] }}
            </a>
            <div class="dropdown-menu">
              @foreach($slotsOne['signups'] as $signup)
                <p class="dropdown-item">
                  <strong>{{ $signup['participant'] }}</strong>
                    Score:
                    @if($signup['score'] == -999)
                      <em>Not found</em>
                    @else
                      <em>{{ $signup['score'] }}</em>
                    @endif
                    Eligibile:
                      <em>
                      @if($signup['eligible'] == 1)
                        Yes
                      @elseif($signup['eligible'] == 0)
                        No
                      @else
                        {{ $signup['eligible'] }}
                      @endif
                    </em>
                </p>
              @endforeach
            </div>
          </div>
        @endforeach

        <h4>Lab Session 2 Timeslots</h4>
        @foreach($sessionTwoSlots as $key => $slotsTwo)

          <div class="dropdown show mb-4">
            <a class="btn btn-info dropdown-toggle" href="#" role="button"
               style="width: 400px;"
               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ date('D M j \a\t g:i', strtotime($slotsTwo['datetime'])) }}
              ::
              {{ $slotsTwo['numSignedUp'] }} / {{ $slotsTwo['numRequested'] }}
            </a>
            <div class="dropdown-menu">
              @foreach($slotsTwo['signups'] as $signupTwo)
                <p class="dropdown-item">
                  <strong>{{ $signupTwo['participant'] }}</strong>
                  @if($signupTwo['score'] == -999)
                    <em>Not found</em>
                  @else
                    <em>{{ $signupTwo['score'] }}</em>
                  @endif
                    Eligibile:
                      <em>
                      @if($signupTwo['eligible'] == 1)
                        Yes
                      @elseif($signupTwo['eligible'] == 0)
                        No
                      @else
                        {{ $signupTwo['eligible'] }}
                      @endif
                    </em>
                </p>
              @endforeach
            </div>
          </div>
        @endforeach
      </div>
  </div>
  <div class="row"><div class="col-12 mb-4" style="height:300px;"></div></div>
</div>
@stop
