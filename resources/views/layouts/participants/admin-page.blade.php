
@extends('layouts.login-master')


@section('content')
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script>

function convertTZ(date) {
    return new Date((typeof date === "string" ? new Date(date) : date).toISOString());   
}

var in_session = {{ $in_session }};
var roomTotal = parseInt("{{ count(\Teamwork\User::where('in_room',1)->where('id','!=',1)->get()) }}");
var time_remaining = null;
var session_count = null;
var happened = false;
var session_begun = false;
var subsession_length = 120;
var current_session = parseInt('{{ $user->current_session }}');
var max_sessions = parseInt('{{ $user->max_sessions }}');
var itv;
$( document ).ready(function() {
  options = {
          item: function(values) {
            return `<tr id='${values.participant_id}'>
                      <td class='participant_id'>${values.participant_id}</td>
                      <td class='group_id'>${values.group_id}</td>
                      <td class='group_size'><span class='group_size_${values.group_id}'></span></td>
                      <td class='activity'><span class='green'>${values.active}</span></td>
                      <td class='group_role'>${values.group_role}</td>
                      <td>
                            <div class="dropdown">
                              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Functions
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" onclick="$.get('/force-refresh-user/${values.participant_id}')" href="#">Refresh User</a>
                                <a class="dropdown-item" onclick="$.get('/force-refresh-group/${values.participant_id}')" href="#">Refresh Group</a>
                              </div>
                            </div>
                          </td>
                    </tr>`;
          } ,
          valueNames: ['participant_id','group_id','group_size','activity','group_role']
        }
  adminTable = new List('admin-table',options);
    adminTable.on('searchComplete',function(e){
          if($(`#search_type`).val() != 'all' && !happened){
            console.log('event3');
             happened = true;
              adminTable.search($('#search').val(),[$(`#search_type`).val()]);
             
          }else{
            if (happened){
              console.log('event2');
              happened = false;
            }
            
          }
        });
      

  if(localStorage.getItem('num_sessions') && current_session){
    $('#num_sessions').val(localStorage.getItem('num_sessions'));
  }
  console.log('{{ $groupMembers }}');
  //console.log('{{ $time_remaining }}');
  if('{{ $time_remaining }}' != ''){
    session_begun = true;
    $('#begin').attr('disabled',true);
      time_remaining = parseInt('{{ $time_remaining }}');
      session_count = current_session;
      itv = setInterval(function(){

          console.log(time_remaining);
            time_remaining -= 1;
            if(time_remaining == 0){
                if( session_count.toString() === $('#num_sessions').val().toString()){
                  //session_count += 1;
                  //$('#session_count').text(session_count);
                  time_remaining = null;
                  $('#num_sessions').attr('disabled',true);
                  $('#session1').remove();
                  $('#session2').remove();
                  $('#begin').attr('disabled',false);
                  adminTable.clear();
                }
                else{
                  session_count += 1;
                  $('#session_count').text(session_count);
                  time_remaining = subsession_length;
                }
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

  //$('#assign').on('click',function(event){
    //$.get('/reassign',function(data){setTimeout(function(){null},5000)});
    
  //});

  $('#force').on('click',function(e){
    $.get('/force-refresh');
  });

  $('#begin').on('click',function(e){
    session_begun = true;
    $.ajax({
      type: "POST",
      
      url: '/begin-session',
      data:{num_sessions:$('#num_sessions').val(),_token: "{{ csrf_token() }}"},
      success: function(data){
        
        $('#num_sessions').attr('disabled',true);
        $(`<h4 id='session1'>Current session: <span id='session_count'>1</span>/${$('#num_sessions').val()}</h4>`).insertAfter('#num_sessions');
        $(`<h4 id='session2'>Time until next session: <span id='session_timer'>0:45</span></h4>`).insertAfter('#num_sessions');
        $('#begin').attr('disabled',true);

        time_remaining = subsession_length;
        session_count = 1;
        clearInterval(itv);
        itv = setInterval(function(){

          console.log(time_remaining);
            time_remaining -= 1;
            if(time_remaining == 0){
                if( session_count.toString() === $('#num_sessions').val().toString()){
                  //session_count += 1;
                  //$('#session_count').text(session_count);
                  time_remaining = null;
                  $('#num_sessions').attr('disabled',true);
                  $('#session1').remove();
                  $('#session2').remove();
                  $('#begin').attr('disabled',false);
                  adminTable.clear();
                }
                else{
                  session_count += 1;
                  $('#session_count').text(session_count);
                  time_remaining = subsession_length;
                }
            }

            $('#session_timer').text(time_remaining > 0 ? time_remaining : 0);
        },1000);
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

  $('th').on('click',function(event){
    $('#tablediv').scrollTop(0);
  });




  Pusher.logToConsole = true;
  console.log("{{ config('app.PUSHER_APP_KEY') }}");
  //console.log('tourd');

    var pusher = new Pusher("{{ config('app.PUSHER_APP_KEY') }}", {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('player-joined-room', function(data) {
      

      roomTotal += 1;
      adminTable.remove('participant_id',data['user']['participant_id']);
      adminTable.add({
        participant_id:data['user']['participant_id'],
        group_id:'WaitingRoom',
        activity:"<span style='color:green'>Active</span>",
        group_role:data['user']['group_role']

      });
      //$('.group_size_WaitingRoom').text($('.group_size_WaitingRoom').length);
      group_num = $("tr:contains('WaitingRoom'):contains('Active')").length;
      $('.group_size_WaitingRoom').text(group_num);
      //adminTable.reIndex();


      });

    channel.bind('player-left-room', function(data) {
        roomTotal -= 1;
        adminTable.remove('participant_id',data['participant_id']);
        group_num = $("tr:contains('WaitingRoom'):contains('Active')").length;
        $('.group_size_WaitingRoom').text(group_num);
        //$('.group_size_WaitingRoom').text($('.group_size_WaitingRoom').length);
        //adminTable.reIndex();
      });

    channel.bind('session-begun',function(data) {
      if(!session_begun){
        $('#num_sessions').val(data['user']['max_sessions']);
        $('#num_sessions').attr('disabled',true);
          $(`<h4 id='session1'>Current session: <span id='session_count'>1</span>/${$('#num_sessions').val()}</h4>`).insertAfter('#num_sessions');
          $(`<h4 id='session2'>Time until next session: <span id='session_timer'>0:45</span></h4>`).insertAfter('#num_sessions');
          $('#begin').attr('disabled',true);

          time_remaining = subsession_length;
          session_count = 1;
          clearInterval(itv);
          itv = setInterval(function(){

            console.log(time_remaining);
              time_remaining -= 1;
              if(time_remaining == 0){
                  if( session_count.toString() === $('#num_sessions').val().toString()){
                    //session_count += 1;
                    //$('#session_count').text(session_count);
                    time_remaining = null;
                    $('#num_sessions').attr('disabled',true);
                    $('#session1').remove();
                    $('#session2').remove();
                    $('#begin').attr('disabled',false);
                    adminTable.clear();
                }
                  else{
                    session_count += 1;
                    $('#session_count').text(session_count);
                    time_remaining = subsession_length;
                  }
              }

              $('#session_timer').text(time_remaining > 0 ? time_remaining : 0);
          },1000);
      }
    });

    channel.bind('send-to-task', function(data) {
        adminTable.remove('participant_id',data['user']['participant_id']);
        adminTable.add({
          participant_id:data['user']['participant_id'],
          group_id:data['user']['group_id'],
          activity:"<span style='color:green'>Active</span>",
          group_role:data['user']['group_role']

        });
        //$(`.group_size_${data['user']['group_id']}`).text($(`.group_size_${data['user']['group_id']}`).length);
        group_num = $(`tr:contains('${data['user']['group_id']}'):contains('Active')`).length;
        $(`.group_size_${data['user']['group_id']}`).text(group_num);
        //adminTable.reIndex();

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
    channel.bind('status-changed', function(data) {
      console.log("yep");
      user_row = adminTable.get('participant_id',data['user']['participant_id'])[0];
      values = user_row.values();
      switch(data['user']['status']){
        case 'Active':
          values['activity'] = `<td class='activity'><span style='color:green'>Active</span></td>`;
          
          break;
        case 'Inactive':
          values['activity'] = `<td class='activity'><span style='color:red'>Inactive</span></td>`;
          break;
        case 'Idle':
          values['activity'] = `<td class='activity'><span style='color:#b0b02b'>Idle</span></td>`;
          break;
        default:
          values['activity'] = '';
          break;

      }
      //values['activity'] = data['user']['status'];
      user_row.values(values);
      if(data['user']['in_room']){
            console.log('this')
            group_num = $("tr:contains('WaitingRoom'):contains('Active')").length;
            $('.group_size_WaitingRoom').text(group_num);
          }
          else{
            group_num = $(`tr:contains('${data['user']['group_id']}'):not(:contains('Inactive'))`).length;
            $(`.group_size_${data['user']['group_id']}`).text(group_num);
          }
    })


});

</script>

<style>
  thead { position: sticky; top: 0; z-index: 1; }

tbody {     /* Just for the demo          */
    overflow-y: hidden;   /* Hide the horizontal scroll */
}
  .list {
  font-family:sans-serif;
}
td, th {
  padding:10px; 
  border:solid 1px #eee;
}

input {
  border:solid 1px #ccc;
  border-radius: 5px;
  padding:7px 14px;
  margin-bottom:10px
}
input:focus {
  outline:none;
  border-color:#aaa;
}
.sort {
  padding:4px 15px;
  border-radius: 6px;
  border:none;
  display:inline-block;
  color:#fff;
  text-decoration: none;
  background-color: #809dba!important;
}
.sort:hover {
  text-decoration: none;
  background-color:#1b8aba;
}
.sort:focus {
  outline:none;
}
.sort:after {
  display:inline-block;
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid transparent;
  content:"";
  position: relative;
  top:-10px;
  right:-5px;
}
.sort.asc:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-top: 5px solid #fff;
  content:"";
  position: relative;
  top:4px;
  right:-5px;
}
.sort.desc:after {
  width: 0;
  height: 0;
  border-left: 5px solid transparent;
  border-right: 5px solid transparent;
  border-bottom: 5px solid #fff;
  content:"";
  position: relative;
  top:-4px;
  right:-5px;
}
  </style>

<div class="container" style='max-width:90%'>
  <div id="notify">
      <div class="ads" style='width:1px'>
      </div>
  </div>
    <div class="row justify-content-center vertical-center">

      <div class="col-md-3 p-4">
        <div class="text-center">
              <button style='display:inline-block' class="btn btn-lg btn-primary"  id="home" onclick='window.location.href="/admin-menu"'>Admin Home</button>
              <button style='display:inline-block' class="btn btn-lg btn-primary" value="1" id="historical-data" onclick="window.location.href='/historical-data'">Historical Data</button>
            </div>
            <hr />
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
                @if($user->current_session)
                  <select disabled id='num_sessions'>
                @else
                  <select id='num_sessions'>
                @endif
                  <option value="1">1</option>
                  <option  value="2">2</option>
                  <option value="3">3</option>
                  <option selected='selected' value="4">4</option>
              </select>
              @if($user->current_session)
                  <h4 id='session1'>Current session: <span id='session_count'>{{ $user->current_session }}</span>/{{ $user->max_sessions }}</h4>
                  <h4 id='session2'>Time until next session: <span id='session_timer'>{{ $time_remaining }}</span></h4>
              @endif
            </div>
        <hr />
        <div class="text-center">
              <button style='background-color:red' class="btn btn-lg btn-primary" value="1" id="force">Refresh All</button><p></p>
              <button style='background-color:red' class="btn btn-lg btn-primary" onclick='window.location.href="/clear-room"' value="1" id="clear">Clear Room / End Session</button><p></p>
              
            </div>

         
      </div>
      <div class='col-md-8' id='admin-table' style='border:1px solid black;padding:15px'>
        <div class='text-center'>
          <h3> Session Info </h3>
          <hr>
          <div style='display:block; text-align:center; margin:auto'>
                <h5 style='display:inline-block;text-align:center; margin:auto'>Search: </h5>
              <input class='search' id='search' style='display:inline-block;text-align:center; margin:auto'/>
              <select class='form-control' id='search_type' style='display:inline-block;text-align:center; margin:auto;width:auto'>
                <option value='all' selected='selected'>
                  All Columns
                </option>
                <option value='participant_id'>
                  p_id
                </option>
                <option value='group_id'>
                  g_id
                </option>
                <option value='group_size'>
                  g_size
                </option>
                <option value='activity'>
                  status
                </option>
                <option value='group_role'>
                  g_role
                </option>
              </select>
          </div>
          <br />
          <div id='tablediv' style='max-height:75vh; overflow-y: auto;'>
            <table style='margin:auto'>
              <tr>
                  <th>
                    <a class='sort' data-sort='participant_id' href='#'>p_id</a>
                  </th>
                  <th>
                    <a class='sort' data-sort='group_id' href='#'>g_id</a>
                  </th>
                  <th>
                    <a class='sort' data-sort='group_size' href='#'>g_size</a>
                  </th>
                  <th>
                    <a class='sort' data-sort='activity' href='#'>status</a>
                  </th>
                  <th>
                    <a class='sort' data-sort='group_role' href='#'>g_role</a>
                  </th>
                  <th>
                  </th>
                </tr>
              <tbody class='list'>
                @foreach($waitingRoomMembers as $key => $w_mem)
                    <tr id='{{ $w_mem->participant_id }}'>
                      <td class='participant_id'>{{ $w_mem->participant_id }}</td>
                      <td class='group_id'>WaitingRoom</td>
                      <td class='group_size'><span class='group_size_WaitingRoom'>{{ \Teamwork\User::where('in_room',1)->where('status','Active')->count() }}</span></td>
                      @if($w_mem->status == 'Active')
                        <td class='activity'><span style='color:green'>Active</span></td>
                      @elseif($w_mem->status == 'Inactive')
                        <td class='activity'><span style='color:red'>Inactive</span></td>
                      @endif
                      <td class='group_role'>{{ $w_mem->group_role }}</td>
                      <td>
                        <div class="dropdown">
                          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Functions
                          </button>
                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" onclick="$.get(`/force-refresh-user/{{ $w_mem->participant_id }}`)" href="#">Refresh User</a>
                            <a class="dropdown-item" onclick="$.get(`/force-refresh-group/{{ $w_mem->participant_id }}`)" href="#">Refresh Group</a>
                          </div>
                        </div>
                      </td>
                    </tr>

                @endforeach
                @foreach($groupMembers as $group_id => $group)
                    @foreach($group as $key => $member)
                        <tr id='{{ $member->participant_id }}'>
                          <td class='participant_id'>{{ $member->participant_id }}</td>
                          <td class='group_id'>{{ $member->group_id }}</td>
                          <td class='group_size'><span class='group_size_{{ $member->group_id }}'>{{ \Teamwork\User::where('group_id',$member->group_id)->where('status','!=','Inactive')->count() }}</span></td>
                          @if($member->status == 'Active')
                            <td class='activity'><span style='color:green'>Active</span></td>
                          @elseif($member->status == 'Inactive')
                            <td class='activity'><span style='color:red'>Inactive</span></td>
                          @else
                            <td class='activity'><span style='color:#b0b02b'>Idle</span></td>
                          @endif
                          <td class='group_role'>{{ $member->group_role }}</td>
                          <td>
                            <div class="dropdown">
                              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Functions
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" onclick="$.get(`/force-refresh-user/{{ $member->participant_id }}`)" href="#">Refresh User</a>
                                <a class="dropdown-item" onclick="$.get(`/force-refresh-group/{{ $member->participant_id }}`)" href="#">Refresh Group</a>
                              </div>
                            </div>
                          </td>
                        </tr>
                    @endforeach
                @endforeach
                
                
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
      
    </div>
</div>
@stop
