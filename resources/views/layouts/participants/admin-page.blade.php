
@extends('layouts.login-master')


@section('content')

<script>

var in_session = {{ $in_session }};
var roomTotal = parseInt("{{ count(\Teamwork\User::where('in_room',1)->where('id','!=',1)->get()) }}");
$( document ).ready(function() {

  $('#session_toggle').on('click',function(e) {
    //colors = {0:'red !important',1:'green !important'};
    //texts = {0:'Study Open',1:'Study Closed'};
    in_session = !in_session;
    $('#session_toggle').val(in_session);
    $('#session_toggle').text(in_session ? 'Study Is Open' : 'Study Is Closed');
    $.get('/toggle-session',function(data){console.log(data)});
  });

  $('#assign').on('click',function(event){
    $.get('/assign-groups',function(data){console.log(data)});
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
    var creditors = [];
    $('input[type=checkbox][name=participant_id]').each(function(data){
      creditors.push($(this).attr('value'));

    });
    console.log(creditors);
    $.ajax({
      type: "POST",
      
      url: '/give-credit',
      data:{'creditors':creditors,_token: "{{ csrf_token() }}"},
      success: function(data){
        console.log(data);
      }
    });
  })

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
          $('#waiting-room-members').append($('<option>', {
              value: data['user']['id'],
              text: data['user']['participant_id'],
              id:data['user']['participant_id']
          }));


      });

    channel.bind('player-left-room', function(data) {
        roomTotal -= 1;
        console.log(data);
        $('#wait_num').text((roomTotal < 0 ? 0 : roomTotal).toString());
        $('#'+data['user']['participant_id']).remove();
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
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="assign">Assign groups</button>
            </div>

         
      </div>
      <div class='col-md-6'>
        <div class='text-center'>
          <h2> Study Info</h2>
          <hr />
              <h3>Waiting Room members: (<span id='wait_num'>{{ count($waitingRoomMembers) }}</span>)</h3>
              <select style='max-height:200px;width:75%' multiple id='waiting-room-members'>
              @foreach($waitingRoomMembers as $key => $w_mem)
                <option value='{{ $w_mem->id }}' id='{{ $w_mem->participant_id }}'>
                  {{ $w_mem->participant_id }}
                </option>
              @endforeach
              </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="text-center">
              <h3>Credit Getters</h3>
              <table>
                <tr>
                  <td>
                    <input type='checkbox' id='check_all' />
                  </td>
                  <td colspan='2' style='float:left;padding-left:15px'>
                    <b>Check All</b>
                  </td>
                </tr>
                @foreach($credit_getters as $key => $cg)
                  <tr>
                    <td>
                      <input type='checkbox' name='participant_id' value='{{ $cg->id }}' />
                    </td>
                    <td>
                      {{ $cg->participant_id }}
                    </td>
                    <td>{{ $cg->signature_date }}
                    </td>
                  </tr>
                @endforeach
              </table>
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="credit">Give Credit</button>
            </div>
      </div>
    </div>
</div>
@stop
