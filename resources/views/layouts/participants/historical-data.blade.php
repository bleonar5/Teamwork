
@extends('layouts.login-master')

@section('css')
  <link rel="stylesheet" href="{{ URL::asset('css/historical.css') }}">
@stop

@section('content')
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script>

//CLEARS SEARCHES AND FILTERS, RESETS
function clearFilter(){

  $('#start_date').val('');
  $('#end_date').val('');
  $('#search_type').val('all');
  $('#search').val('');
  
  adminTable.search();
}

//CHECK ALL CHECKBOXES
function checkAll(){
  $('input[type="checkbox"]:enabled:not(:checked)').prop('checked',true);
}

//UNCHECK ALL
function uncheckAll(){
  $('input[type="checkbox"]:enabled:checked').prop('checked',false);
}

//TAKE ALL NEWLY CHECKED BOXES AND CONFIRM THOSE AS PAID IN DB
//UNCONFIRMING PAY IS NOT AN OPTION
function confirmPayment(){

  session_ids = [];

  //FOR EACH CHECKED INPUT, SEND THAT SESSION ID TO SERVER
  $('input:checked:enabled').each(function(){
    session_ids.push(parseInt($(this).attr('id').split('_')[1]));
    $(this).prop('disabled',true)
  });
  
  $.post('/confirm-paid',{
    _token: '{{ csrf_token() }}',
    session_ids: session_ids
  });

}

var happened = false;

$( document ).ready(function() {
  //SETS CELL NAMES FOR LIST.JS TABLE
  options = {
    valueNames: ['participant_id','session_id','session_time','type','num_subsessions','total_sessions','group_ids','role','eligible','paid','notes']
  };

  //INITIALIZES TABLE (SORT,SEARCH,FILTER)
  adminTable = new List('admin-table',options);

  //HANDLES SEARCH FOR SPECIFIC COLUMNS
  adminTable.on('searchComplete',function(e){
    if($(`#search_type`).val() != 'all' && !happened){
       happened = true;
        adminTable.search($('#search').val(),[$(`#search_type`).val()]);
       
    }else{
      if (happened){
        happened = false;
      }
    }
  });

  //HANDLES DATE FILTER
  //NEED DIFFERENT RESPONSE FOR STARTDATE AND ENDDATE
  $('#start_date').on('change',function(event){
    //IF THE NEW VALUE IS A VALID DATE
    if ($('#start_date').val()){
      //RESET TABLE
      adminTable.filter();

      //GET DATE
      start_date = Date.parse($('#start_date').val());

      //IF AN END DATE HAS BEEN SET
      if($('#end_date').val()){
        //GET END DATE
        end_date = Date.parse($('#end_date').val());

        //FILTER TABLE TO REFLECT START-END WINDOW
        adminTable.filter(function(item){
          session_date = Date.parse(item.values().session);
          if (session_date >= start_date && session_date <= end_date){
            return true;
          }
          else
            return false;
        });
      }
      //IF NO END DATE YET
      else{
        //FILTER FROM START DATE TO PRESENT
        adminTable.filter(function(item){
          session_date = Date.parse(item.values().session);
          if (session_date >= start_date)
            return true;
          else
            return false;
        });
      }
    }
  });

  //HANDLES DATE FILTER
  //NEED DIFFERENT RESPONSE FOR STARTDATE AND ENDDATE
  $('#end_date').on('change',function(event){
    //IF END DATE IS VALID
    if ($('#end_date').val()){
      //RESET TABLE
      adminTable.filter();

      //GET END DATE
      end_date = Date.parse($('#end_date').val());

      //IF START DATE IS ALREADY SET
      if($('#start_date').val()){
        //GET START DATE
        start_date = Date.parse($('#start_date').val());
        //FILTER FOR WINDOW
        adminTable.filter(function(item){
          session_date = Date.parse(item.values().session);
          if (session_date >= start_date && session_date <= end_date){
            return true;
          }
          else
            return false;
        });
      }
      //IF NOT SET
      else{
        //FILTER FROM DAWN OF TIME UNTIL END DATE
        adminTable.filter(function(item){
          session_date = Date.parse(item.values().session);
          if (session_date <= end_date)
            return true;
          else
            return false;
        });
      }
    }
    
  });

  //WHEN "EDIT" IS CLICKED TO EDIT A NOTE
  $('.link').on('click',function(event) {
    //WHICH SESSION
    id = $(this).attr('id').split('_')[1];

    //GET NOTE TEXT
    notetext = $(`#notes_${id.toString()}`).text();

    //DISPLAY NOTE TEXT TO EDIT IN MODAL
    $('#note_text').val(notetext);
    $('#ok-edit-note').attr('whichnote',id);
    $('#edit_note').modal('toggle');
    
  });

  //WHEN NEW NOTE EDIT IS SUBMITTED
  $('#ok-edit-note').on('click',function(event){
    //UPDATE CELL VALUE AND DISPLAY
    $(`#notes_${$('#ok-edit-note').attr('whichnote')}`).text($('#note_text').val());
    $(`#link_${$('#ok-edit-note').attr('whichnote')}`).text($('#note_text').val());

    //SAVE IN DB
    $.post('/save-notes',{
      _token:'{{ csrf_token() }}',
      note:$('#note_text').val(),
      id:$('#ok-edit-note').attr('whichnote')


    })
  });

  //SCROLL TO TOP OF TABLE WHEN COLUMN IS SORTED
  $('th').on('click',function(event){
    $('#tablediv').scrollTop(0);
  });

  //ESTABLISHES PUSHER CONNECTION
  //TO COMMUNICATE WITH SERVER    
  Pusher.logToConsole = true;

  var pusher = new Pusher('{{ config("app.PUSHER_APP_KEY") }}', {
    cluster: 'us2'
  });

  //CHANNEL FOR ADMIN AND WAITING ROOM
  var channel = pusher.subscribe('my-channel');

  //IF ANOTHER ADMIN MAKES A CHANGE, DYNAMICALLY UPDATE TABLE
  channel.bind('session-changed', function(data){
    if(data['session']['paid']){
      $(`#paid_${data['session']['id']}`).attr('checked',true);
    }
    if(data['session']['notes']){
      $(`#notes_${data['session']['id']}`).text(data['session']['notes']);
      $(`#link_${data['session']['id']}`).text(data['session']['notes']);
    }
  });
});

</script>

<style>


  </style>

<div class="container" style='max-width:90%'>
  <div id="notify">
      <div class="ads" style='width:1px'>
      </div>
  </div>
  <div class="row justify-content-center vertical-center">
    <div class='col-md-12' id='admin-table' style='border:1px solid black;padding:15px'>
      <div class='text-center'>
        <div>
        <h3>Historical Data</h3>
        <div>
          <button class='btn-primary btn' onclick='window.location.href="/admin-menu"' >Admin Home</button>
          <button class='btn-primary btn' onclick='window.location.href="/admin-page"' >Session Data</button>
        </div>
        <hr>
        <div class='col-md-12' style='display:inline-block; text-align:center; margin:auto'>
          <h5 style='display:inline-block;text-align:center; margin:auto'>Search: </h5>
          <input class='search' id='search' style='display:inline-block;text-align:center; margin:auto'/>
          <select class='form-control' id='search_type' style='display:inline-block;text-align:center; margin:auto;width:auto'>
            <option value='all' selected='selected'>
              All Columns
            </option>
            <option value='participant_id'>
              p_id
            </option>
            <option value='group_ids'>
              g_ids
            </option>
            <option value='session_id'>
              s_id
            </option>
            <option value='notes'>
              notes
            </option>
          </select>
        </div><p></p>
        <div class='col-md-12'style='display:inline-block; text-align:center; margin:auto'>
          <h5 style='display:inline-block;text-align:center; margin:auto'>Date Range: </h5>
          <input style='display:inline-block;text-align:center; margin:auto' type='datetime-local' id='start_date' name='start_date' />
          <h5 style='display:inline-block;text-align:center; margin:auto'> to </h5>
          <input style='display:inline-block;text-align:center; margin:auto' type='datetime-local' id='end_date' name='end_date' />
          <button class='btn-primary btn' id='clearFilter' style='margin-left:10px;' onclick='clearFilter()'>Clear filter</button>
          <button class='btn-primary btn' id='confirm_payment' style='margin-left:10px;' onclick='checkAll()'>Check All</button>
          <button class='btn-primary btn' id='confirm_payment' style='margin-left:10px;' onclick='uncheckAll()'>Uncheck All</button>
          <button class='btn-primary btn' id='confirm_payment' style='margin-left:10px;' onclick='confirmPayment()'>Confirm pay</button>
        </div>
        <br />
        <br />
        <div id='tablediv' style='max-height:75vh; overflow-y: auto;'>
          <table style='margin:auto'>
            <thead>
              <tr class='header'>
                <th>
                  <a class='sort' data-sort='participant_id' href='#'>p_id</a>
                </th>
                <th>
                  <a class='sort' data-sort='session_id' href='#'>s_id</a>
                </th>
                <th>
                  <a class='sort' data-sort='session_time' href='#'>s_time</a>
                </th>
                <th>
                  <a class='sort' data-sort='type' href='#'>type</a>
                </th>
                <th>
                  <a class='sort' data-sort='num_subsessions' href='#'># subs</a>
                </th>
                <th>
                  <a class='sort' data-sort='total_sessions' href='#'># seshes</a>
                </th>
                <th>
                  <a class='sort' data-sort='group_ids' href='#'>g_ids</a>
                </th>
                <th>
                  <a class='sort' data-sort='role' href='#'>role</a>
                </th>
                <th>
                  <a class='sort' data-sort='eligible' href='#'>elig?</a>
                </th>
                <th>
                  <a class='sort' data-sort='paid' href='#'>paid?</a>
                </th>
                <th>
                  <a class='sort' data-sort='notes' href='#'>notes</a>
                </th>
                  
                  
              </tr>
            </thead>
            <tbody class='list'>
              @foreach($userSessions as $key => $session)
                <tr id='{{ $session->id }}'>
                  <td class='participant_id'>{{ $session->participant_id }}</td>
                  <td class='session_id'>{{ $session->session_id }}</td>
                  <td class='session_time'>{{ $session->created_at->setTimezone('EST') }}</td>
                  <td class='type'>{{ $session->type }}</td>
                  <td class='num_subsessions'>{{ $session->num_subsessions }}</td>
                  <td class='total_sessions'>{{ $session->total_sessions }}</td>
                  <td class='group_ids'>{{ $session->group_ids }}</td>
                  <td class='role'>{{ $session->group_role }}</td>
                  <td class='eligible'>
                    @if($session->eligible)
                      Yes
                    @else
                      No
                    @endif
                  </td>
                  <td class='paid'>
                    @if($session->paid)
                      <span id='paidspan_{{ $session->id }}' style='display:none'>1</span>
                      <input type='checkbox' class='paid_box' name='paid_{{ $session->id }}' id='paid_{{ $session->id }}' checked disabled>
                    @else
                      <span id='paidspan_{{ $session->id }}' style='display:none'>0</span>
                      <input type='checkbox' class='paid_box' name='paid_{{ $session->id }}' id='paid_{{ $session->id }}' >
                    @endif
                  </td>
                  <td class='notes'><span style='display:none' id='notes_{{ $session->id }}'>{{ $session->notes }}</span><a href='#' style="max-width:150px;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" id='link_{{ $session->id }}' class='link'>
                    @if($session->notes)
                      {{ $session->notes }}
                    @else
                      Edit
                    @endif
                  </a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>        
    </div>      
  </div>
</div>

<div class="modal fade" id="edit_note">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title text-center">
          Edit Note 
          </h4>
          
        </div>
        <div class="modal-body text-center">
          <textarea class='form-control' id='note_text' name='note'></textarea> 
          <p></p>
          <button whichnote='' class="btn btn-lg btn-primary pull-right" id="ok-edit-note" data-dismiss="modal" type="button">Ok</button>

        </div>
      </div><!-- modal-content -->
    </div><!-- modal-dialog -->
  </div><!-- modal -->
@stop
