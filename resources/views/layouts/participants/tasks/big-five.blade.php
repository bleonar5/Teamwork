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

      $(".alert-danger").hide();

      // Form validation
      // Comes before instructionPaginator so the on click handler is bound first
      $("#next").on('click', function(event) {
        $(".alert-danger").hide();
        $('.input:visible .form-check-input').each(function(){
          var name = $(this).attr("name");
          if ($("input:radio[name=" + name + "]:checked").length == 0) {
            $(".alert-danger").show();
            event.stopImmediatePropagation();
            return;
          }
        })
      });


      instructionPaginator(function(){
        $(".container").hide();
        $("#big-five-form").submit();
      });

    });

</script>

<div class="container">
  <div class="row">
    <div class="col-md-12 text-center">
      <div class="alert alert-danger" role="alert">Please make sure you answer all questions before continuing.</div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <h5 class="mb-lg-4">
        In relation to other people of the same gender who are roughly the same age, I would say that I:
      </h5>
      <form id="big-five-form" action="/big-five" method="post">
        {{ csrf_field() }}

            @for($i = 0; $i < count($statements); $i++)
              @if($i == 0 || $i % 10 == 0)
                <div id="inst_{{ intdiv($i, 10) + 1}}" class="inst">
                  <table class="table table-striped table-sm mt-lg-4 big-five">
                    <tr>
                      <td class="blank"></td>
                      <th class="key">
                        <div><span>Very<br>True</span></div>
                      </th>
                      <th class="key">
                        <div><span>Somewhat<br>True</span></div>
                      </th>
                      <th class="key">
                        <div><span>Neither true<br>nor untrue</span></div>
                      </th>
                      <th class="key">
                        <div><span>Moderately<br>Untrue</span></div>
                      </th>
                      <th class="key">
                        <div><span>Very<br>Untrue</span></div>
                      </th>
                    </tr>
              @endif
                      <tr>
                        <td class="text-left">
                          {{ $statements[$i]['statement'] }}
                        </td>
                        @for($j = 5; $j > 0; $j--)
                          <td class="text-center input">
                              <input class="form-check-input" type="radio" name="{{ $statements[$i]['number'] }}" value="{{ $j }}">
                          </td>
                        @endfor
                      </tr>
              @if(($i + 1) % 10 == 0)
                  </table>
                </div>
              @endif
            @endfor
      </form>
      <div id="instr_nav" class="text-center">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
        <input class="btn btn-primary instr_nav btn-lg" type="button" name="next" id="next" value="Next &#8680;">
        <span class="text-primary ml-md-4 text-lg" id="pagination-display">
          <span id="curr-page">1</span> / {{ count($statements) / 10 }}
        </span>
      </div>
    </div>
    </div>
  </div>
</div>

@stop
