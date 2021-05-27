
@extends('layouts.login-master')


@section('content')
<script src="//cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
<script>

function convertTZ(date) {
    return new Date((typeof date === "string" ? new Date(date) : date).toISOString());   
}

function clearFilter(){
  $('#start_date').val('');
  $('#end_date').val('');
  $('#search_type').val('all');
  $('#search').val('');
  
  adminTable.search();
}

function checkAll(){
  $('input[type="checkbox"]').attr('checked',true);
}

function uncheckAll(){
  $('input[type="checkbox"]').attr('checked',false);
}

function confirmPayment(){
  //adminTable.filter();
  session_ids = [];
  $('input:checked:enabled').each(function(){
    session_ids.push(parseInt($(this).attr('id').split('_')[1]));
  });
  
  $.post('/confirm-paid',{
    _token: '{{ csrf_token() }}',
    session_ids: session_ids
  });
}

var happened = false;

$( document ).ready(function() {
  options = {
          valueNames: ['participant_id','session_id','session_time','type','num_subsessions','total_sessions','group_ids','role','eligible','paid','notes']
        }
  adminTable = new List('admin-table',options);
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
  $('#start_date').on('change',function(event){

    if ($('#start_date').val()){
      console.log('change');
      adminTable.filter();
      start_date = Date.parse($('#start_date').val());
      if($('#end_date').val()){
        end_date = Date.parse($('#end_date').val());
        adminTable.filter(function(item){
          session_date = Date.parse(item.values().session);
          if (session_date >= start_date && session_date <= end_date){
            return true;
          }
          else
            return false;
        });
      }
      else{
        console.log('no end')
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

  $('#end_date').on('change',function(event){
    if ($('#end_date').val()){
      adminTable.filter();
      end_date = Date.parse($('#end_date').val());
      if($('#start_date').val()){
        start_date = Date.parse($('#start_date').val());
        adminTable.filter(function(item){
          session_date = Date.parse(item.values().session);
          if (session_date >= start_date && session_date <= end_date){
            return true;
          }
          else
            return false;
        });
      }
      else{
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

  $('.link').on('click',function(event) {
    id = $(this).attr('id').split('_')[1];
    notetext = $(`#notes_${id.toString()}`).text();
    $('#note_text').val(notetext);
    $('#ok-edit-note').attr('whichnote',id);
    $('#edit_note').modal('toggle');
    
  });

  $('#ok-edit-note').on('click',function(event){
    console.log(`#notes_${$('#ok-edit-note').attr('whichnote')}`)
    $(`#notes_${$('#ok-edit-note').attr('whichnote')}`).text($('#note_text').val());
    $.post('/save-notes',{
      _token:'{{ csrf_token() }}',
      note:$('#note_text').val(),
      id:$('#ok-edit-note').attr('whichnote')


    })
  });
      
  Pusher.logToConsole = true;

    var pusher = new Pusher('{{ config("app.PUSHER_APP_KEY") }}', {
      cluster: 'us2'
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('session-changed', function(data){
      if(data['session']['paid']){
        $(`#paid_${data['session']['id']}`).attr('checked',true);
      }
      if(data['session']['notes']){
        $(`#notes_${data['session']['id']}`).text(data['session']['notes']);
      }
    });
});

</script>

<style>
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
      <div class='col-md-12' id='admin-table' style='border:1px solid black;padding:15px'>
        <div class='text-center'>
          <div>
          <h3>Historical Data</h3>
          <div>
            <button class='btn-primary btn' onclick='window.location.href="/admin-menu"' >Admin Home</button>
            <button class='btn-primary btn' onclick='window.location.href="/admin-page"' >Session Data</button>
          </div>
          <hr>
          <div class='col-md-5' style='display:inline-block; text-align:center; margin:auto'>
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
          <div class='col-md-8'style='display:inline-block; text-align:center; margin:auto'>
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
          <div style='max-height:75vh; overflow-y: auto;'>
            <table style='margin:auto'>
              <tr>
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
              <tbody class='list'>
                @foreach($userSessions as $key => $session)
                    <tr id='{{ $session->id }}'>
                      <td class='participant_id'>{{ $session->participant_id }}</td>
                      <td class='session_id'>{{ $session->session_id }}</td>
                      <td class='session_time'>{{ $session->created_at }}</td>
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
                          <input type='checkbox' class='paid_box' name='paid_{{ $session->id }}' id='paid_{{ $session->id }}' checked>
                        @else
                          <input type='checkbox' class='paid_box' name='paid_{{ $session->id }}' id='paid_{{ $session->id }}' >
                        @endif
                      </td>
                      <td class='notes'><span style='display:none' id='notes_{{ $session->id }}'>{{ $session->notes }}</span><a href='#' id='link_{{ $session->id }}' class='link'>Edit</a></td>
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
