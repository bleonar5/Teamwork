@extends('layouts.master')

@section('js')
  <script src="{{ URL::asset('js/memory.js') }}"></script>
  <script src="{{ URL::asset('js/image-preloader.js') }}"></script>
@stop

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/tasks.css') }}">
@stop

@section('content')

<script>

    var tests = <?php echo  $enc_tests; ?>;
    $( document ).ready(function() {

      var preloadImages = <?= json_encode($imgsToPreload); ?>
      // Preload all images
      preload(preloadImages);

      var callback = function() {
        $("#memory-form").submit();
      };
      var memory = new Memory(tests, isReporter=false, callback);
      memory.begin();

      $('.memory-nav').on('click', function(event) {
        if(memory.hasPopup()) {
          event.stopImmediatePropagation();
          return;
        }
        memory.advance();
        event.preventDefault();
      });

      $('.select-all').on('change', function(event) {
        var response = $(this).attr('name');
        $('.no-selection[name="'+response+'"]').prop('checked', false);
      });

      // Target images
      $('.target-nav').on('click', function(event) {
        memory.navTarget($(this).attr('name'));
        event.preventDefault();
      });

      $(document).keydown(function(event) {
        var key = event.key;
        if((key == 1 || key == 2 || key == 3) && ($(".memory-img").is(":visible") || $(".story-choices").is(":visible"))) memory.advanceImageTest(key);
      });

      $("#popup-continue").on('click', function(event) {
        $("#popup").modal('toggle');
        //memory.advance();
      })
    });

</script>

<div class="container">
  <div class="row vertical-center">
    <div class="col-md-12 text-center">
      <form id="memory-form" action="/memory-individual" method="post">
        {{ csrf_field() }}
        @foreach($tests as $key => $test)

          @if($test['task_type'] == 'intro')

          @endif {{-- End if type = intro --}}

          @if($test['task_type'] == 'images')

            @foreach($test['blocks'] as $b_key => $block)

              @if($block['type'] == 'review' || $block['type'] == 'practice_review')
                <div class="memory memory-review review" id="memory_{{ $key }}_{{ $b_key }}">
                  <div class="float-right text-primary timer" id="timer_{{ $key }}_{{ $b_key }}"></div><br>
                  <h4>{{ $block['text'] }}</h4>
                  @if(count($block['targets']) == 1)
                    <img class="target-img" src="{{ $test['directory'].$block['targets'][0] }}">
                    @if($block['type'] == 'practice_review')
                      <h4>Press continue when you’re ready!</h4>
                    @endif
                    <div class="text-center mt-lg-2">
                      <input class="btn btn-primary memory-nav btn-lg mt-lg-4"
                             type="button" name="next"
                             id="continue_{{ $key }}_{{ $b_key }}"
                             value="Continue">
                    </div>
                  @else
                    @foreach($block['targets'] as $img_key => $img)
                      <img class="target-img target target-{{ $img_key }}" src="{{ $test['directory'].$img }}">
                    @endforeach

                    <div class="text-center mt-lg-2">
                      <input class="btn btn-primary target-nav btn-lg" type="button" name="back" id="back" value="Change Perspective">
                    </div>
                  @endif {{-- End multiple images --}}
                </div>
              @endif {{-- End if blocktype = review --}}

              @if($block['type'] == 'practice_test')
                <div class="memory test practice-test" id="memory_{{ $key }}_{{ $b_key }}">
                  <h4>{{ $block['prompt'] }}</h4>
                  <h4>Type [1], [2], or [3]</h4>
                  <h4><em>Note: You need to type the number on your keyboard</em></h4>
                  <img class="memory-img mt-lg-4" src="{{ $test['directory'].$block['img'] }}">
                  <div class="row text-center justify-content-center">
                    <div class="col-sm-3"><h2>1</h2></div>
                    <div class="col-sm-3"><h2>2</h2></div>
                    <div class="col-sm-3"><h2>3</h2></div>
                  </div>
                  <input type="hidden" name="response_{{ $key }}_{{ $b_key }}"
                         id="response_{{ $key }}_{{ $b_key }}">
                </div>
              @endif {{-- End if blocktype = practice_test --}}

              @if($block['type'] == 'test')
                <div class="memory test" id="memory_{{ $key }}_{{ $b_key }}">
                  <h4>{{ $block['prompt'] }}</h4>
                  <h4>Type [1], [2], or [3]</h4>
                  <h4><em>Note: You need to type the number on your keyboard</em></h4>
                  <img class="memory-img mt-lg-4" src="{{ $test['directory'].$block['img'] }}">
                  @if($block['show_numbers'] == 'true')
                    <div class="row text-center justify-content-center">
                      <div class="col-md-3"><h2>1</h2></div>
                      <div class="col-md-3"><h2>2</h2></div>
                      <div class="col-md-3"><h2>3</h2></div>
                    </div>
                  @endif
                  <input type="hidden" name="response_{{ $key }}_{{ $b_key }}"
                         id="response_{{ $key }}_{{ $b_key }}">
                </div>
              @endif {{-- End if blocktype = test --}}

              @if($block['type'] == 'text')
                <div class="memory memory-text text" id="memory_{{ $key }}_{{ $b_key }}">
                  @if(isset($block['header']))
                    <h2 class="text-primary">{{ $block['header'] }}</h2>
                  @endif
                  <h4>{{ $block['text'] }}</h4>
                  <div class="text-center">
                    <input class="btn btn-primary memory-nav btn-lg"
                           type="button" name="next"
                           id="continue_{{ $key }}_{{ $b_key }}"
                           value="Continue">
                  </div>
                </div>
              @endif {{-- End if blocktype = text --}}

            @endforeach {{-- End foreach block --}}
          @endif {{-- End if type = images --}}

          @if($test['task_type'] == 'words')
            @foreach($test['blocks'] as $b_key => $block)

              @if($block['type'] == 'review')
                <div class="memory memory-review review" id="memory_{{ $key }}_{{ $b_key }}">
                  <div class="float-right text-primary timer" id="timer_{{ $key }}_{{ $b_key }}"></div><br>
                  <h4>{{ $block['text'] }}</h4>
                  @foreach($block['targets'] as $word_key => $word)
                    <div class="target-word target target-{{ $word_key }}">
                      <h1 class="text-center">{{ $word }}</h1>
                    </div>
                  @endforeach
                </div>
              @endif {{-- End if blocktype = review --}}

              @if($block['type'] == 'text')
                <div class="memory memory-text text" id="memory_{{ $key }}_{{ $b_key }}">
                  @if(isset($block['header']))
                    <h2 class="text-primary">{{ $block['header'] }}</h2>
                  @endif
                  <h4>{{ $block['text'] }}</h4>
                  <div class="text-center">
                    <input class="btn btn-primary memory-nav btn-lg"
                           type="button" name="next"
                           id="continue_{{ $key }}_{{ $b_key }}"
                           value="Continue">
                  </div>
                </div>
              @endif {{-- End if blocktype = text --}}

              @if($block['type'] == 'test' || $block['type'] == 'practice_test')
                <div class="memory test" id="memory_{{ $key }}_{{ $b_key }}">
                  <h4>{{ $block['prompt'] }}</h4>
                  <h4>Select all that apply, then click "Next"</h4>
                  <div class="row justify-content-md-center word-choices">
                    @foreach($block['choices'] as $c_key => $choice)
                      <div class="col-md-3 form-group">
                        <h2>
                        <label
                               for="response_{{ $key }}_{{ $b_key }}">
                               {{ $choice }}
                        </label><br>
                        <input class="checkbox select-all" type="checkbox"
                               name="response_{{ $key }}_{{ $b_key }}[]"
                               value="{{ $c_key + 1 }}">
                        </h2>
                      </div>
                    @endforeach
                    <input type="checkbox" class="no-selection"
                           name="response_{{ $key }}_{{ $b_key }}[]"
                           value="0" checked>
                  </div>
                  <div class="text-center">
                    <input class="btn btn-primary memory-nav btn-lg"
                           type="button" name="next"
                           id="continue_{{ $key }}_{{ $b_key }}"
                           value="Next">
                  </div>
                </div>
              @endif {{-- End if blocktype = test --}}

            @endforeach {{-- End for each block --}}
          @endif {{-- End if type = words --}}

          @if($test['task_type'] == 'story')
            @foreach($test['blocks'] as $b_key => $block)

              @if($block['type'] == 'review')
                <div class="memory memory-review review" id="memory_{{ $key }}_{{ $b_key }}">
                  <div class="float-right text-primary timer" id="timer_{{ $key }}_{{ $b_key }}"></div><br>
                  <h4>{{ $block['text'] }}</h4>
                  @if(array_key_exists('subtext', $block))
                    <h5>{{ $block['subtext'] }}</h5>
                  @endif
                  @foreach($block['targets'] as $num => $target)
                    <h4 class="text-left mt-lg-4">{{ $num + 1 }}: {{ $target }}</h4>
                    <hr>
                  @endforeach
                  <div class="text-center mt-lg-2">
                    @if(!$block['review_time'])
                      <input class="btn btn-primary memory-nav btn-lg mt-lg-4"
                             type="button" name="next"
                             id="continue_{{ $key }}_{{ $b_key }}"
                             value="Continue">
                    @endif
                  </div>
                </div>
              @endif {{-- End if blocktype = review --}}

              @if($block['type'] == 'text')
                <div class="memory memory-text text" id="memory_{{ $key }}_{{ $b_key }}">
                  @if(isset($block['header']))
                    <h2 class="text-primary">{{ $block['header'] }}</h2>
                  @endif
                  <h4>{{ $block['text'] }}</h4>
                  <div class="text-center">
                    <input class="btn btn-primary memory-nav btn-lg"
                           type="button" name="next"
                           id="continue_{{ $key }}_{{ $b_key }}"
                           value="Continue">
                  </div>
                </div>
              @endif {{-- End if blocktype = text --}}

              @if($block['type'] == 'practice_test')
                <div class="memory test practice-test" id="memory_{{ $key }}_{{ $b_key }}">
                  <h4>{{ $block['prompt'] }}</h4>
                  <h4>Type [1], [2], or [3]</h4>
                  <div class="row">
                    <div class="col-md-6 offset-md-3 text-left story-choices">
                      @foreach($block['choices'] as $c_key => $choice)
                          <h4>
                            {{ $c_key + 1 }}) {{ $choice }}
                          </h4>
                      @endforeach
                      <input type="hidden" name="response_{{ $key }}_{{ $b_key }}"
                             id="response_{{ $key }}_{{ $b_key }}">
                    </div>
                  </div>
                </div>
              @endif {{-- End if blocktype = practice_test --}}

              @if($block['type'] == 'test')
              <div class="memory test practice-test" id="memory_{{ $key }}_{{ $b_key }}">
                <h4>{{ $block['prompt'] }}</h4>
                <h4>Type [1], [2], or [3]</h4>
                <div class="row">
                  <div class="col-md-6 offset-md-3 text-left story-choices">
                    @foreach($block['choices'] as $c_key => $choice)
                        <h4>
                          {{ $c_key + 1 }}) {{ $choice }}
                        </h4>
                    @endforeach
                    <input type="hidden" name="response_{{ $key }}_{{ $b_key }}"
                           id="response_{{ $key }}_{{ $b_key }}">
                 </div>
               </div>
              </div>
              @endif {{-- End if blocktype = test --}}

            @endforeach {{-- End for each block --}}
          @endif {{-- End if type = story --}}

        @endforeach {{-- End foreach test --}}

      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="popup" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title text-center" id="popup-text"></h4>
      </div>
      <div class="modal-body text-center">
          <button class="btn btn-lg btn-primary" id="popup-continue" type="button">Continue</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop
