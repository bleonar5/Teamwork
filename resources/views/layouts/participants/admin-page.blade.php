
@extends('layouts.login-master')


@section('content')

<script>

var in_session = {{ $in_session }};
$( document ).ready(function() {

  $('#session_toggle').on('click',function(e) {
    //colors = {0:'red !important',1:'green !important'};
    //texts = {0:'Study Open',1:'Study Closed'};
    in_session = !in_session;
    $('#session_toggle').val(in_session);
    $('#session_toggle').text(in_session ? 'Study Open' : 'Study Closed');
    $.get('/toggle-session',function(data){console.log(data)});
  });

  $('#get-participants').on('click',function(event){

  });
});

</script>

<div class="container">
  <div id="notify">
      <div class="ads" style='width:1px'>
      </div>
  </div>
    <div class="row justify-content-center vertical-center">
      <div class="col-md-6 p-4">
        <div class="text-center">
              @if($in_session)
                <button style='background-color:green' class="btn btn-lg btn-primary" value="0" id="session_toggle">Study Open</button>
              @else
                <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="session_toggle">Study Closed</button>
              @endif
            </div>
      </div>
      <div class="col-md-6 p-4">
        <div class="text-center">
              <h3>Credit Getters</h3>
              <ul>
                @foreach($credit_getters as $key => $cg)
                  <li>
                    {{ $cg->participant_id }} : {{ $cg->signature_date }}
                  </li>
                @endforeach
              </ul>
            </div>
      </div>
    </div>
</div>
@stop
