<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\User;
use Teamwork\Group;
use Teamwork\Session;
use Teamwork\GroupTask;
use Teamwork\Events\SendToTask;
use Teamwork\Jobs\SendTaskEvent;
use Teamwork\Jobs\AssignGroups;
use Teamwork\Jobs\SessionComplete;
use Teamwork\Events\EndSubsession;
use Teamwork\Events\SessionBegun;
use Teamwork\Events\SessionChanged;
use Teamwork\Events\StatusChanged;
use Teamwork\Events\ForceRefresh;
use Teamwork\Events\ForceRefreshUser;
use Teamwork\Events\ForceRefreshGroup;
use Teamwork\Events\PlayerJoinedWaitingRoom;
use Teamwork\Events\PlayerLeftWaitingRoom;
use Teamwork\Events\StudyOpened;
use Teamwork\Events\StudyClosed;
use Illuminate\Support\Facades\Log;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


//MANAGES FUNCTIONS FOR THE WAITING ROOM AS WELL AS THE ADMIN PAGE
class WaitingRoomController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    #MAIN WAITING ROOM FUNCTION, USED TO ENTER WAITING ROOM AS PARTICIPANT
    public function getWaitingRoom(Request $request){
        //GRAB THE CURRENT GROUP TASK, IF EXISTS
        $group_task = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));

        //IF TASK IS ALREADY ACTIVE, SEND TO TASK
        //CRYPTO AND MEMORY ARE SPECIAL TASKS THAT INCLUDE VIDEO CONFERENCING
        //TASK-ROOM IS A WRAPPER PAGE FOR TASKS THAT CONTAINS THE VIDEO CONFERENCING FUNCTIONALITIES
        if($group_task->name != "WaitingRoom"){
            if($group_task->name === 'Cryptography'){
                return redirect('/task-room');
            }
            elseif($group_task->name === "Memory"){
                return redirect('/task-room');
            }
            else{
                return redirect('/get-individual-task');
            }
        }

        //UNPACK PARAMETERS TO CHECK IF CRYPTO OR MEMORY
        $parameters = unserialize($group_task->parameters);
        if($parameters->task === '1'){
            $task = 1;
            $task_name = 'Cryptography';
        }
        else{
            $task = 2;
            $task_name = 'Memory';
        }

        //GET ENTRY FOR THIS USER
        $user_id = \Auth::user()->id;
        $this_user = User::where('id',$user_id)->first();

        if(is_null($this_user->group_role)){
            return view('layouts.participants.no-role-assigned');
        }

        //WE USE SIGNATURE_DATE AS A WAY OF INDICATING WHO ENTERED THE WAITING ROOM AT ALL, FOR CREDIT GRANTING PURPOSES IN THE PILOT STAGE
        $this_user->signature_date = \Carbon\Carbon::now();
        //EITHER 0 FOR NOT-IN-WAITING-ROOM, 1 FOR CRYPTO, OR 2 FOR MEMORY
        $this_user->in_room = $task;
        //STATUS IS USED FOR DISPLAYING SESSION DATA ON ADMIN-PAGE
        $this_user->status = 'Active';

        $this_user->save();

        //SEND EVENT TO LET ADMIN PAGE KNOW THAT USER IS ACTIVE AND IN WAITING ROOM
        event(new StatusChanged($this_user));

        //ADMIN USER CONTAINS INFO ABOUT CURRENT SESSION
        $admin = User::where('id',1)->first();
        //IF SESSION IS ACTIVE, DISPLAY TIMER INDICATING COUNTDOWN TO NEXT SUBSESSION
        if($admin->current_session){
            $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

            $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
       
            $session_length = 165;

            $time_remaining = $session_length * $admin->current_session - $time_elapsed;
        }
        else
          $time_remaining = NULL;
        

        $room_users = User::where('in_room',$task)->where('id','!=',1)->orderBy('updated_at','ASC')->get();

        event(new PlayerJoinedWaitingRoom($this_user));

        return view('layouts.participants.waiting-room')
            ->with('users',$room_users)
            ->with('task',$task)
            ->with('PUSHER_APP_KEY',config('app.PUSHER_APP_KEY'))
            ->with('time_remaining',$time_remaining);
    }

    #PERIODICALLY PING TO SHOW USER STILL IN WAITING ROOM
    public function stillHere(Request $request){
        $this_user = User::find(\Auth::user()->id);

        $this_user->touch();

        $room_users = User::where('in_room',1)->where('status','Active')->where('id','!=',1)->get();

        foreach($room_users as $key => $room_user) {

            $diff = $room_user->updated_at->diffInSeconds(\Carbon\Carbon::now());

            if($diff > 5){

                $room_user->status = 'Inactive';
                $room_user->save();

                event(new StatusChanged($room_user));
                
            }
        }

        return '200';
    }

    public function browserError(Request $request){
        return view('layouts.participants.browser-error');
    }

    #ADMIN FUNCTIONS 
    # ------------------------------------------------------

    #DISPLAYS ADMIN PAGE
    public function adminPage(Request $request){
        #IS THERE AN ACTIVE SESSION
        $admin = User::where('id',1)->first();
        $in_session = $admin->in_room;

        $waitingRoomMembers = User::where('in_room',1)->where('id','!=',1)->get();

        $activeGroupTasks = GroupTask::where('started',1)->where('name',"Cryptography")->where('order',1)->where('created_at','>',\Carbon\Carbon::now()->startOfDay())->get();

        $groups = [];

        foreach($activeGroupTasks as $key => $ac_task){
            #GRABS THE CRYPTO TASK RATHER THAN THE CRYPTO INTRO TASK
            $real_task = GroupTask::where('group_id',$ac_task->group_id)->where('name','Cryptography')->where('order',2)->first();

            if(!$real_task->completed)
                $groups[] = $real_task->group_id;
        }

        $groupMembers = User::whereIn('group_id',$groups)->get()->groupBy('group_id');

        $time_remaining = null;


        if($admin->current_session){
            $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

            $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
       
            $session_length = 165;

            $time_remaining = $session_length * $admin->current_session - $time_elapsed;

            $total_time = $session_length * $admin->max_sessions;
        }

        return view('layouts.participants.admin-page')
                    ->with('user',$admin)
                    ->with('in_session',$in_session)
                    ->with('waitingRoomMembers',$waitingRoomMembers)
                    ->with('groupMembers',$groupMembers)
                    ->with('time_remaining',$time_remaining);
    }

    #SWITCH SESSION TO OPEN OR CLOSED
    public function toggleSession(Request $request){

        $in_session = User::where('id',1)->first();
        $in_session->in_room = !$in_session->in_room;
        $in_session->save();

        if($in_session->in_room)
            event(new StudyOpened($in_session));
        else
            event(new StudyClosed($in_session));

        return '200';
    }

    #FORCE PARTICIPANT PAGE TO REFRESH
    public function forceRefreshUser($id, Request $request){

        $this_user = User::where('participant_id',$id)->first();

        event(new ForceRefreshUser($this_user));

        return '200';
    }

    #FORCE GROUP PAGE TO REFRESH
    public function forceRefreshGroup($id, Request $request){

        $this_user = User::where('participant_id',$id)->first();

        $group = User::where('group_id',$this_user->group_id)->get();

        foreach($group as $key => $g){

            event(new ForceRefreshUser($g));

        }
        
        return '200';
    }

    #SET PARTICIPANT STATUS TO 'IDLE'
    public function setIdle(Request $request){

        $user = User::find($request->user_id);
        $user->status = 'Idle';
        $user->save();

        event(new StatusChanged($user));

        return '200';

    }

    #SET PARTICIPANT STATUS TO ACTIVE
    public function setActive(Request $request){

        $user = User::find($request->user_id);
        $user->status = 'Active';
        $user->save();

        event(new StatusChanged($user));

        return '200';

    }

    #BEGIN 1-4 ROUND SESSION WITH CURRENT USERS IN WAITING ROOM
    public function beginSession(Request $request){

        #IF THERE AREN'T AT LEAST 3 PPL IN WAITING ROOM, DON'T START SESSION
        $in_room = User::where('in_room',1)->where('id','!=',1)->where('status','Active')->get()->shuffle();
        if(count($in_room) < 3)
            return 'NO';

        $admin = User::where('id',1)->first();
        $admin->current_session = 0;
        $admin->max_sessions = $request->num_sessions;
        $admin->save();

        $time = \Teamwork\Time::create(['user_id' => \Auth::user()->id, 'type' => 'session']);
        $time->recordStartTime();
        $session_length = 165;

        for($i=0; $i<$request->num_sessions; $i++){
            (new AssignGroups(''))->dispatch('')->delay(\Carbon\Carbon::now()->addSeconds($session_length * $i));
        }

        event(new SessionBegun($admin));

        return '200';


    }

    #GET PARTICIPANT'S ROLE
    public function getRole(Request $request){
        $user = User::where('id',$request->id)->first();
        return $user->group_role;
    }

    #DISPLAYS 'CLOSED' PAGE TO PARTICIPANTS
    public function studyClosed(Request $request){
        return view('layouts.participants.study-closed');
    }

    #SETS DATE OF UPCOMING SESSION
    public function submitDate(Request $request){

        $admin = User::where('id',1)->first();
        $admin->signature = $request->date;
        $admin->save();

        return '200';
    }

    #CHANGE PARTICIPANT STATUS AND UPDATE ADMIN PAGE
    public function statusChange(Request $request){

        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();

        event(new StatusChanged($user));

        return '200';
    }

    #CLEAR OUT WAITING ROOM AND END SESSION
    public function clearRoom(Request $request){

        $admin = User::where('id',1)->first();
        $admin->current_session = null;
        $admin->max_sessions = null;
        $admin->save();

        $group_tasks = GroupTask::where('name','Cryptography')->get();

        foreach($group_tasks as $key => $gt){
            $gt->completed = 1;
            $gt->save();
        }

        $waiters = User::where('in_room',1)->where('id','!=',1)->get();

        foreach($waiters as $key => $w){
            $w->in_room = 0;
            $w->status = 'Inactive';
            $w->save();
        }

        return redirect('/admin-page');
    }

    #SAVE NOTE FOR USER-SESSION IN HISTORICAL DATA TABLE
    public function saveNotes(Request $request){

        $sesh = Session::find($request->id);
        $sesh->notes = $request->note;
        $sesh->save();

        event(new SessionChanged($sesh));

        return '200';
    }

    #DISPLAY MAIN ADMIN MENU
    public function adminMenu(Request $request){
        return view('layouts.participants.admin-menu');
    }

    #DISPLAY HISTORICAL DATA PAGE
    public function historicalData(Request $request){
        $userSessions = Session::orderBy('session_id','desc')->get();


        return view('layouts.participants.historical-data')
                ->with('userSessions',$userSessions);
    }

    #MARK PARTICIPANTS AS PAID
    public function confirmPaid(Request $request){

        $session_ids = $request->session_ids;

        foreach($session_ids as $key => $sid){

            $sesh = Session::find($sid);
            $sesh->paid = 1;
            $sesh->save();

            event(new SessionChanged($sesh));

        }

        return '200';
    }

    function shuffle_assoc($list) {
      if (!is_array($list)) return $list;

      $keys = array_keys($list);
      shuffle($keys);
      $random = array();
      foreach ($keys as $key)
        $random[$key] = $list[$key];

      return $random;
    }

    public function makeGroups(Request $request){

        //$leaders = User::where('in_room',true)->where('group_role','leader')->get();
        //$followers = User::where('in_room',true)->where('group_role','follower')->get();

        $leaders = array();
        $followers = array();

        for($i = 0;$i < 30; $i++){
            $leaders[$i] = array('past_fs'=> array());
            $followers[30 + ($i * 2)] = array('past_ls' => array());
            $followers[30 + ($i * 2)+1] = array('past_ls' => array());
        }


        $ls = array();
        $fps = array();

        foreach($leaders as $key => $leader){
            $ls[$key] = array('past_fs' => array_merge(array(),$leader['past_fs']), 'current_fs' => array(),'assigned'=>false);
        }

        foreach($followers as $key => $follower){
            $fs[$key] = array('past_ls' => array_merge(array(),$follower['past_ls']),'assigned'=>false);
        }
        

        $trying = true;
        $count = 0;

        while($trying) {
            Log::debug($count);
            
            $count += 1;

            if($count >= 50){
                Log::debug('gave up');
                $ret_array = $this->test_assignment($leaders,$followers,4,true);
            }
            else
                $ret_array = $this->test_assignment($leaders,$followers,4,false);

            $ret_ls = $ret_array[0];
            $ret_fs = $ret_array[1];
            Log::debug($ret_ls);


            if($ret_array[0] != NULL){
                $trying = false;
            }

            if($count > 10)
                $trying = false;

        }
        return $ret_array;


    }



    private function test_assignment(array $leaders, array $followers,$depth = 4,$participant_repeat=false) {
        if($depth == 0)
            return [$leaders,$followers];

        Log::debug('depth: ');
        Log::debug($depth);

        $ls = array();
        $fs = array();

        foreach($leaders as $id => $leader){
            $ls[$id] = array('past_fs' => array_merge(array(),$leader['past_fs']), 'current_fs' => array(),'assigned'=>false);
        }

        foreach($followers as $id => $follower){
            $fs[$id] = array('past_ls' => array_merge(array(),$follower['past_ls']),'assigned'=>false);
        }

        $ls = $this->shuffle_assoc($ls);
        $fs = $this->shuffle_assoc($fs);

       // Log::debug('shuffled leaders');
        //Log::debug($ls);

        $keep_going = true;

        while($keep_going){
            Log::debug('looping...');

            $not_assigned_followers = 0;

            foreach($fs as $id => $follower) {
                if (!$follower['assigned'])
                    $not_assigned_followers += 1;
            }

            $not_assigned_leaders = 0;

            foreach($ls as $id => $leader) {
                if (!$leader['assigned'])
                    $not_assigned_leaders += 1;
            }

            if ($not_assigned_followers == 0){
                $keep_going = false;
                continue;
            }
            if($not_assigned_leaders == 0){
                $keep_going = false;
                continue;
            }



            $max_past = array(-1,NULL);

            foreach($fs as $id => $follower){

                if(($follower['assigned'] == false) and (count($follower['past_ls']) > $max_past[0]) )
                    $max_past = array(count($follower['past_ls']),$id);

            }

            if ($max_past[0] == -1){
                Log::debug($fs);
                Log::debug('gave up follower');
                return array(NULL,NULL);
            }

            $max_past_l = array(-1,NULL);

            foreach($ls as $id => $leader){
                Log::debug($leader);
                if($leader['assigned'] == false){
                    Log::debug('not assigned');
                    $past_fs_count = 0;
                    foreach($leader['past_fs'] as $fid=>$follower){

                        if($fid == $max_past[1])
                            $past_fs_count += 1;
                    }
                    if ($participant_repeat and $past_fs_count > $max_past_l[0]){
                        Log::debug('participant_repeat');
                        $max_past_l = array($past_fs_count,$id);
                    }
                    else{
                        if (count($leader['past_fs']) > $max_past_l[0] and !array_key_exists($max_past[1],$leader['past_fs'])) {
                            Log::debug('found one: ');
                            Log::debug($id);
                            $max_past_l = array(count($leader['past_fs']),$id);
                        }
                    }
                    Log::debug(count($leader['past_fs']));
                    Log::debug($max_past_l[0]);
                    Log::debug(array_key_exists($max_past[1],$leader['past_fs']) ? 'exists' : 'doesnt');
                    Log::debug($max_past_l);
                }
            }
            //Log::debug('follower: ');
            //Log::debug($max_past[1]);
            //Log::debug('leader: ');
            //Log::debug($max_past_l[1]);

            if($max_past_l[0] == -1){
                Log::debug($ls);
                Log::debug($max_past[1]);
                Log::debug('gave up leader');
                return array(NULL, NULL);
            }

            $fs[$max_past[1]]['past_ls'][$max_past_l[1]] = array();
            $ls[$max_past_l[1]]['past_fs'][$max_past[1]] = array();

            $ls[$max_past_l[1]]['current_fs'][$max_past[1]] = array();

            $fs[$max_past[1]]['assigned'] = true;
            //Log::debug($ls[$max_past_l[1]]);
            //Log::debug(count($ls[$max_past_l[1]]['current_fs']));
            //Log::debug('sproing');

            if(count($ls[$max_past_l[1]]['current_fs']) == 2)
                $ls[$max_past_l[1]]['assigned'] = true;

            //Log::debug($ls);
        }
    Log::debug($ls);

    return $this->test_assignment($ls,$fs,$depth-1,$participant_repeat);



    }
}
