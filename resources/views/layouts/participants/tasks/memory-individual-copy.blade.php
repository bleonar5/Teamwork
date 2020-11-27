@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/memory.js') }}"></script>
@stop

@section('content')

<script>

    var tests = <?php echo  $enc_tests; ?>;
    $( document ).ready(function() {
      console.log(tests);
      var memory = new Memory(tests);
      memory.begin();

      $('.practice-nav').on('click', function(event) {
        memory.advance();
        event.preventDefault();
      });

      // Target images
      $('.target-img').hide();

      $(document).keypress(function(event) {
        console.log('Handler for .keypress() called. - ' + event.charCode);
        var key = event.charCode;
        if(key == 49 || key == 50 || key == 51) memory.advanceImageTest(key);
      });
    });

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      @foreach($tests as $key => $test)

        @if($test['task_type'] == 'images')
          @foreach($test['practices'] as $p_key => $practice)
            <div class="memory memory-practice practice-intro" id="memory_{{ $key }}_0">
              <h2>{{ $practice['intro'] }}</h2>
              <img src="{{ $test['directory'].$practice['images']}}">
              <div class="text-center">
                <input class="btn btn-primary practice-nav btn-lg" type="button" name="next" id="next" value="Next &#8680;">
              </div>
            </div>

            @foreach($practice['tests'] as $t_key => $t)
              <div class="memory memory-practice" id="memory_{{ $key }}_{{ $t_key + 1 }}">
                <h2> {{ $t['prompt'] }}</h2>
                <img src="{{ $test['directory'].$t['img'] }}">
                <div class="row text-center">
                  <div class="col-md-2 offset-md-3">
                    <h4>1</h4>
                  </div>
                  <div class="col-md-2">
                    <h4>2</h4>
                  </div>
                  <div class="col-md-2">
                    <h4>3</h4>
                  </div>
                </div>
              </div>
            @endforeach
          @endforeach

          @foreach($test['target_groups'] as $g_key => $group)
            <div class="memory memory-test memory-intro" id="memory_{{ $key }}_0">
              <h2>{{ $group['intro'] }}</h2>
              @foreach($group['images'] as $g_img_key => $g_img)
                <img class="target-img" src="{{ $test['directory'].$g_img }}">
              @endforeach
              <div class="text-center">
                <input class="btn btn-primary target-img-nav btn-lg" type="button" name="back" id="back" value="&#8678; Back">
                <input class="btn btn-primary target-img-nav btn-lg" type="button" name="next" id="next" value="Next &#8680;">
              </div>
            </div>

          @endforeach
        @endif

      @endforeach

    </div>
  </div>
</div>
@stop
