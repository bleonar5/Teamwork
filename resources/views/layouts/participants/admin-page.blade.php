
@extends('layouts.login-master')


@section('content')

<script>

function convertTZ(date) {
    return new Date((typeof date === "string" ? new Date(date) : date).toISOString());   
}

var in_session = {{ $in_session }};
var roomTotal = parseInt("{{ count(\Teamwork\User::where('in_room',1)->where('id','!=',1)->get()) }}");
var time_remaining = null;
$( document ).ready(function() {
  if(localStorage.getItem('num_sessions')){
    $('#num_sessions').val(localStorage.getItem('num_sessions'))
  }
  console.log('{{ $groupMembers }}');
  //console.log('{{ $time_remaining }}');
  if('{{ $time_remaining }}' != ''){
      time_remaining = parseInt('{{ $time_remaining }}');
      setInterval(function(){
        console.log(time_remaining);
          time_remaining -= 1;
          if(time_remaining == 30){
            $.get('/end-subsession',function(data){
              //window.location.reload();
            });
          }
          if(time_remaining == 0 ){
            if('{{ $user->current_session }}' != '{{ $user->max_sessions }}')
                $('#assign').click();
            else
                window.location.reload();
          }
          $('#session_timer').text(time_remaining > 0 ? time_remaining : 0);
      },1000);

  }

  $('#session_toggle').on('click',function(e) {
    //colors = {0:'red !important',1:'green !important'};
    //texts = {0:'Study Open',1:'Study Closed'};
    in_session = !in_session;
    $('#session_toggle').val(in_session);
    $('#session_toggle').text(in_session ? 'Study Is Open' : 'Study Is Closed');
    $.get('/toggle-session',function(data){console.log(data)});
  });

  $('#assign').on('click',function(event){
    $.get('/assign-groups',function(data){setTimeout(function(){window.location.reload();},5000)});
    
  });

  $('#force').on('click',function(e){
    $.get('/force-refresh');
  });

  $('#begin').on('click',function(e){
    $.ajax({
      type: "POST",
      
      url: '/begin-session',
      data:{num_sessions:$('#num_sessions').val(),_token: "{{ csrf_token() }}"},
      success: function(data){
        $('#assign').click();
      }
    });
  });

  $('#num_sessions').on('click',function(event){
    localStorage.setItem('num_sessions',$(this).val());
  });

  $('#check_all').on('click',function(event){
    if ($(this).is(':checked')){
      $('input[type=checkbox]').each(function(data){
        $(this).prop('checked',true);
      });
    }
    else{
      $('input[type=checkbox]').each(function(data){
        $(this).prop('checked',false);
      });
    }
  });
  
  $('#credit').on('click',function(event){
    var date_start = $('#date_start').val();
    var date_end = $('#date_end').val();
    $.ajax({
      type: "GET",
      
      url: '/get-getters',
      data:{_token:'{{ csrf_token() }}',date_start:convertTZ(date_start).toString().slice(0,33),date_end:convertTZ(date_end).toString().slice(0,33)},
      success: function(data){
        $('#credit_getters').empty();
        console.log(data);
        [...JSON.parse(data)].forEach(datum => $('#credit_getters').append($('<option>', {
              value: datum['id'],
              text: datum['participant_id'],
              id: datum['participant_id']
          })));
      }
    });
  });

  $('#set_date').on('click',function(event){
    $.ajax({
      type: "POST",
      
      url: '/submit-date',
      data:{date:$('#date').val(),_token: "{{ csrf_token() }}",},
      success: function(data){
        console.log(data);
        $('#set_date').text('Set!');
      }
    });
  });



  Pusher.logToConsole = true;
  console.log("{{ config('app.PUSHER_APP_KEY') }}");
  //console.log('tourd');

    var pusher = new Pusher("{{ config('app.PUSHER_APP_KEY') }}", {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('player-joined-room', function(data) {
      //alert(JSON.stringify(data));
          roomTotal += 1;
          $('#wait_num').text((roomTotal < 0 ? 0 : roomTotal).toString());
          //$('#waiting-room-members').append(new Option(data['user']['participant_id'], data['user']['id'],id=data['user']['participant_id']));
          //consol
          if(!$('#'+data['user']['participant_id']+'_waiting').length){
              $('#waiting-room-members').append($('<option>', {
                  value: data['user']['id'],
                  text: data['user']['participant_id'],
                  id:data['user']['participant_id']+'_waiting'
              }));
          }
          


      });

    channel.bind('player-left-room', function(data) {
        roomTotal -= 1;
        console.log(data);
        $('#wait_num').text((roomTotal < 0 ? 0 : roomTotal).toString());
        $('#'+data['user']['participant_id']+'_waiting').remove();
      });
    channel.bind('study-closed', function(data) {
        if(in_session){
          in_session = !in_session;
          $('#session_toggle').val(in_session);
          $('#session_toggle').text(in_session ? 'Study Is Open' : 'Study Is Closed');
        }
      });
    channel.bind('study-opened', function(data) {
        if(!in_session){
          in_session = !in_session;
          $('#session_toggle').val(in_session);
          $('#session_toggle').text(in_session ? 'Study Is Open' : 'Study Is Closed');
        }
      });

});

</script>

<div class="container">
  <div id="notify">
      <div class="ads" style='width:1px'>
      </div>
  </div>
    <div class="row justify-content-center vertical-center">
      <div class="col-md-3 p-4">

        <div class="text-center">
          <h2>Study Controls:</h3>
              @if($in_session)
                <button style='background-color:green' class="btn btn-lg btn-primary" value="0" id="session_toggle">Study Is Open</button>
              @else
                <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="session_toggle">Study Is Closed</button>
              @endif
            </div>
            <hr />
        <div class="text-center">
              <h3>Set next study date:</h3>
              <input type='datetime-local' id='date' name='date' />
              <p></p>
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="set_date">Set Date</button>
            </div>
            <hr / >
        <div class="text-center">
              <button style='background-color:green' class="btn btn-lg btn-primary" value="1" id="begin">Begin Session</button><p></p>
              <h5># of sub-sessions</h5>
              <select id='num_sessions'>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
              </select>
              @if($user->current_session)
                  <h4>Current session: {{ $user->current_session }}/{{ $user->max_sessions }}</h4>
                  <h4>Time until next session: <span id='session_timer'>{{ $time_remaining }}</span></h4>
              @endif
            </div>
        <hr />
        <div class="text-center">
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="assign">Assign groups</button><p></p>
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="force">Force Refresh</button>
            </div>

         
      </div>
      <div class='col-md-6'>
        <div class='text-center'>
          <h2> Study Info</h2>
          <hr />
              <h3>Waiting Room members: (<span id='wait_num'>{{ count($waitingRoomMembers) }}</span>)</h3>
              <select style='max-height:200px;width:75%' multiple id='waiting-room-members'>
              @foreach($waitingRoomMembers as $key => $w_mem)
                <option value='{{ $w_mem->id }}' id='{{ $w_mem->participant_id }}_waiting'>
                  {{ $w_mem->participant_id }}
                </option>
              @endforeach
              </select>
        </div>
        <hr />
        <div class='text-center'>
              <h3>Group Members</h3>
              <hr />
              
              @foreach($groupMembers as $group_id => $group)
                <h4> Group {{ $group_id }}</h4>
                <select style='max-height:200px;width:75%' multiple id='group-members-{{ $group_id }}'>
                  @foreach($group as $key => $member)
                    <option value='{{ $member->id }}' id='{{ $member->participant_id }}'>
                      {{ $member->participant_id }}
                    </option>
                  @endforeach
                </select>
              @endforeach
              
        </div>
      </div>
      <div class="col-md-3">
        <div class="text-center">
              <h3>Credit Getters</h3>
              <hr />
              <h4>Start of date range:</h4>
              <input type='datetime-local' id='date_start' name='date_start' />
              <p></p>
              <h4>End of date range:</h4>
              <input type='datetime-local' id='date_end' name='date_end' />
              <p></p>
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="credit">Get from range</button>
              <p></p>
              <ul id='credit_getters'>
                @foreach($creditGetters as $key => $cg)
                  <li>
                    {{ $cg->participant_id }}
                  </li>
                @endforeach
              </ul>
            </div>
      </div>
    </div>
</div>
@stop
