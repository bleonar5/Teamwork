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
//use Teamwork\Events\SendToTask;
use Illuminate\Support\Facades\Log;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class WaitingRoomController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function userInRoom(Request  $request){
        $user_id = \Auth::user()->id;

        $this_user = User::where('id',$user_id)->first();

        $this_group = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('name','Cryptography')->first();

        

        $this_user->in_room = 1;

        $this_user->save();

        
        

        
        $all_users = User::get();

        #sleep(1);

        event(new PlayerJoinedWaitingRoom($this_user));

        Log::debug($room_users);
        Log::debug($all_users);


        return '200';
    }

    public function browserError(Request $request){
        return view('layouts.participants.browser-error');
    }

    public function toggleSession(Request $request){
        $in_session = User::where('id',1)->first();
        $in_session->in_room = !$in_session->in_room;
        $in_session->save();
        if($in_session->in_room){
            event(new StudyOpened($in_session));
        }
        else{
            event(new StudyClosed($in_session));
        }
        return '200';
    }

    public function forceRefresh(Request $request){
        $openTasks = GroupTask::where('started',1)->where('completed',0)->get();
        foreach($openTasks as $key => $openTask){
            Log::debug($openTask);
            event(new ForceRefresh($openTask));
        }
        return '200';
    }

    public function forceRefreshUser($id, Request $request){
        $user_id = $id;

        $this_user = User::where('participant_id',$user_id)->first();

        event(new ForceRefreshUser($this_user));
        return '200';
    }

    public function forceRefreshGroup($id, Request $request){
        $user_id = $id;

        $this_user = User::where('participant_id',$user_id)->first();

        $group = User::where('group_id',$this_user->group_id)->get();

        foreach($group as $key => $g){
            event(new ForceRefreshUser($g));
        }

        
        return '200';
    }

    public function setIdle(Request $request){
        $user = User::find($request->user_id);
        $user->status = 'Idle';
        $user->save();

        event(new StatusChanged($user));
        return '200';

    }

    public function setActive(Request $request){
        $user = User::find($request->user_id);
        $user->status = 'Active';
        $user->save();

        event(new StatusChanged($user));
        return '200';

    }

    public function testSession(Request $request){
        $last_session = Session::orderBy('created_at','desc')->first();
        $these_sessions = Session::where('created_at',$last_session->created_at)->get();
        return $these_sessions;
    }

    public function beginSession(Request $request){
        $user = User::where('id',1)->first();
        $user->current_session = 0;
        $user->max_sessions = $request->num_sessions;
        $user->save();
        $now = \Carbon\Carbon::now();
        $time = \Teamwork\Time::create(['user_id' => \Auth::user()->id,
                                'type' => 'session']);

        $time->recordStartTime();
        $session_length = 120;
        //(new AssignGroups(''))->dispatch('');//->delay(\Carbon\Carbon::now()->addSeconds(5));
        //(new AssignGroups(''))->dispatch('')->delay(\Carbon\Carbon::now()->addSeconds(45 * 1));
        //(new AssignGroups(''))->dispatch('')->delay(\Carbon\Carbon::now()->addSeconds(5));
        for($i=0; $i<$request->num_sessions; $i++){
            (new AssignGroups(''))->dispatch('')->delay(\Carbon\Carbon::now()->addSeconds($session_length * $i));
        }
        //(new SessionComplete(''))->dispatch('')->delay(\Carbon\Carbon::now()->addSeconds($session_length * $request->num_sessions));
        

        event(new SessionBegun($user));

        return '200';


    }

    public function getRole(Request $request){
        $user = User::where('id',$request->id)->first();
        return $user->group_role;
    }

    public function endSubsession(Request $request){
        #$openTasks = GroupTask::where('started',1)->where('completed',0)->get();
        event(new EndSubsession(User::where('id',1)->first()));
        return '200';
        
    }

    public function studyClosed(Request $request){
        return view('layouts.participants.study-closed');
    }

    public function submitDate(Request $request){
        $user = User::where('id',1)->first();
        $user->signature = $request->date;
        $user->save();
        return '200';
    }

    public function adminPage(Request $request){
        $in_session = User::where('id',1)->first()->in_room;
        $cgs = User::where('signature_date','!=',null)->where('score','!=',1)->orderBy('signature_date','DESC')
                    ->get();


        $waitingRoomMembers = User::where('in_room',1)->where('id','!=',1)->get();

        $activeGroupTasks = GroupTask::where('started',1)->where('name',"Cryptography")->where('order',1)->where('created_at','>',\Carbon\Carbon::now()->startOfDay())->get();
        $groups = [];
        foreach($activeGroupTasks as $key => $ac_task){
            $real_task = GroupTask::where('group_id',$ac_task->group_id)->where('name','Cryptography')->where('order',2)->first();
            Log::debug($real_task);

            if(!$real_task->completed){
                Log::debug('happened');
                $groups[] = $real_task->group_id;
            }
        }
        //Log::debug($groups);

        $groupMembers = User::whereIn('group_id',$groups)->get()->groupBy('group_id');

        $credit_getters = User::where('id','!=',1)->whereNotNull('signature_date')->where('created_at','>',\Carbon\Carbon::now()->startofDay())->where('created_at','<',\Carbon\Carbon::now()->endOfDay())->get();

        $admin = User::where('id',1)->first();
        $time_remaining = null;
        if($admin->current_session){
            $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

            $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
       
            $session_length = 120;

            $time_remaining = $session_length * $admin->current_session - $time_elapsed;
            $total_time = $session_length * $admin->max_sessions;


        }
        



        return view('layouts.participants.admin-page')
                    ->with('user',$admin)
                    ->with('in_session',$in_session)
                    ->with('credit_getters',$cgs)
                    ->with('waitingRoomMembers',$waitingRoomMembers)
                    ->with('groupMembers',$groupMembers)
                    ->with('creditGetters',$credit_getters)
                    ->with('time_remaining',$time_remaining);
    }

    public function assignGroups(Request $request){
        return '200';



    }

    public function reassign(Request $request){
        $admin = User::where('id',1)->first();
        
        
        while(true){
            $task = 1;
            $task_name = "Cryptography";
            $in_room = User::where('in_room',1)->where('id','!=',1)->get()->shuffle();
            #$in_room = (array) $in_room;
            Log::debug($in_room);
            #shuffle($indices);
            #shuffle($in_room);
            if(count($in_room) >= 3){
                $leader = $in_room[0];
                $leader->group_role = 'leader';
                $follower1 = $in_room[1];
                $follower1->group_role = 'follower1';
                $follower2 = $in_room[2];
                $follower2->group_role = 'follower2';

                $group = new Group;
                $group->save();

                $leader->group_id = $group->id;
                $follower1->group_id = $group->id;
                $follower2->group_id = $group->id;

                foreach([$leader,$follower1,$follower2] as $user){
                    try{
                        \DB::table('group_user')
                           ->insert(['user_id' => $user->id,
                                     'group_id' => $group->id,
                                     'created_at' => date("Y-m-d H:i:s"),
                                     'updated_at' => date("Y-m-d H:i:s")]);
                    }

                    catch(\Exception $e){
                        // Will throw an exception if the group ID and user ID are duplicates. Just ignore
                    }
                    if($user->task_id == 0)
                        $user->task_id = rand(1,16);
                    else
                        $user->task_id = (($user->task_id + 1) % 16) + 1;

                }

                if($task == 1){
                    \Teamwork\GroupTask::initializeCryptoTasks($group->id,$randomize=false,$final=$admin->current_session == $admin->max_sessions);
                }
                else{
                    \Teamwork\GroupTask::initializeMemoryTasks($group->id,$randomize=false);
                }

                $group_task = \Teamwork\GroupTask::where('group_id',$group->id)->where('name',$task_name)->orderBy('order','ASC')->first();
                $group_task->task_id = $leader->task_id;
                $group_task->save();
                $leader->in_room = 0;
                $follower1->in_room = 0;
                $follower2->in_room = 0;
                $leader->save();
                $follower1->save();
                $follower2->save();

                event(new SendToTask($leader));
                event(new SendToTask($follower1));
                event(new SendToTask($follower2));


                



            }
            else{
                return '200';
            }

        }



    }

    public function getGetters(Request $request){

        $getters = User::where('id','!=',1)->whereNotNull('signature_date')->where('created_at','>',\Carbon\Carbon::parse($request->date_start))->where('created_at','<',\Carbon\Carbon::parse($request->date_end))->get();
        Log::debug($getters);
        Log::debug(\Carbon\Carbon::parse($request->date_start));
        Log::debug($request->date_start);
        Log::debug(\Carbon\Carbon::parse($request->date_end));
        Log::debug($request->date_end);
        return json_encode($getters);
    }

    public function giveCredit(Request $request){
        Log::debug($request->creditors);
        foreach($request->creditors as $key => $creditor){
            $creditor = User::find((int) $creditor);
            $creditor->score = 1;
            $creditor->save();
        }
        return '200';
    }

    public function statusChange(Request $request){
        $user = User::find($request->id);
        $user->status = $request->status;
        $user->save();
        event(new StatusChanged($user));
        return '200';
    }

    public function getWaitingRoom(Request $request){



        $group_task = \Teamwork\GroupTask::find($request->session()->get('currentGroupTask'));
        Log::debug($group_task);
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

        $parameters = unserialize($group_task->parameters);
        if($parameters->task === '1'){
            $task = 1;
            $task_name = 'Cryptography';
        }

        else{
            $task = 2;
            $task_name = 'Memory';
        }

        $user_id = \Auth::user()->id;

        $this_user = User::where('id',$user_id)->first();

        $this_user->signature_date = \Carbon\Carbon::now();

        $this_user->in_room = $task;
        $this_user->status = 'Active';
        $this_user->save();

        event(new StatusChanged($this_user));

        $admin = User::where('id',1)->first();
        if($admin->current_session){
            $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

            $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
       
            $session_length = 120;

            $time_remaining = $session_length * $admin->current_session - $time_elapsed;

        }
        else{
          $time_remaining = NULL;
        }

        if($group_task->started && $group_task->completed == 0){
            return redirect('/task-room');
        }

        $room_users = User::where('in_room',$task)->where('id','!=',1)->get();



        

        $room_users = User::where('in_room',$task)->where('id','!=',1)->orderBy('updated_at','ASC')->get();
        /*
        $indices = [0,1,2];
        shuffle($indices);
        $assignments = ['leader','follower1','follower2'];
        if(count($room_users) >= 3){
            $group = new Group;
            $group->save();
            $room_users[$indices[0]]->group_role = 'leader';

            $room_users[$indices[1]]->group_role = 'follower1';
            //$room_users[$indices[1]]->group_id = $room_users[$indices[0]]->group_id;
            $room_users[$indices[2]]->group_role = 'follower2';
            //$room_users[$indices[2]]->group_id = $room_users[$indices[0]]->group_id;
            foreach($room_users as $key=>$room_user){
                $room_user->group_id = $group->id;

                try{
                    \DB::table('group_user')
                       ->insert(['user_id' => $room_user->id,
                                 'group_id' => $group->id,
                                 'created_at' => date("Y-m-d H:i:s"),
                                 'updated_at' => date("Y-m-d H:i:s")]);
                  }

                  catch(\Exception $e){
                    // Will throw an exception if the group ID and user ID are duplicates. Just ignore
                  }

                if($room_user->task_id == 0)
                    $room_user->task_id = rand(1,16);
                else
                    $room_user->task_id = (($room_user->task_id + 1) % 16) + 1;
                
                if($room_user->group_role == 'leader'){
                    $room_user->in_room = 0;
                    //$group_task = \Teamwork\GroupTask::firstOrCreate('group_id',$room_user->group_id)->where('name','Cryptography')->first();
                    if ($task == 1){
                        \Teamwork\GroupTask::initializeCryptoTasks($group->id,$randomize=false);
                    }
                    else
                        \Teamwork\GroupTask::initializeMemoryTasks($group->id,$randomize=false);
                    $group_task = \Teamwork\GroupTask::where('group_id',$group->id)->where('name',$task_name)->orderBy('order','ASC')->first();
                    $group_task->task_id = $room_user->task_id;
                    $group_task->save();
                    event(new SendToTask($room_user));
                }
                else{
                    event(new SendToTask($room_user));
                }
                $room_user->save();
                
            }
            
            return redirect('/task-room?clear=true');
        }
        */

        #$this_group = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('name',$task_name)->orderBy('created_at','DESC')->first();
        #if($this_group){
        #    if($this_group->started && $group_task->completed == 0)
        #        return redirect('/task-room');
        #}
        #sleep(1);

        event(new PlayerJoinedWaitingRoom($this_user));
        return view('layouts.participants.waiting-room')
            ->with('users',$room_users)
            ->with('task',$task)
            ->with('PUSHER_APP_KEY',config('app.PUSHER_APP_KEY'))
            ->with('time_remaining',$time_remaining);
    }

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

    public function saveNotes(Request $request){
        $sesh = Session::find($request->id);
        $sesh->notes = $request->note;
        $sesh->save();
        event(new SessionChanged($sesh));
        return '200';
    }

    public function getMemoryWaitingRoom(Request $request){

        $user_id = \Auth::user()->id;

        $this_user = User::where('id',$user_id)->first();

        $this_user->in_room = 2;
        $this_user->save();


        $group_task = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('name','Memory')->orderBy('created_at','DESC')->first();

        if($group_task->started && $group_task->completed == 0){
            return redirect('/task-room/memory');
        }

        $room_users = User::where('in_room',2)->get();



        foreach($room_users as $key => $room_user) {
            $diff = $room_user->updated_at->diffInSeconds(\Carbon\Carbon::now());
            if($diff > 30){
                $room_user->in_room = 0;
                $room_user->save();
            }
        }

        $room_users = User::where('in_room',2)->get();

        $indices = [0,1,2];
        shuffle($indices);
        $assignments = ['leader','follower1','follower2'];
        if(count($room_users) == 3){
            $group = new Group;
            $group->save();
            $room_users[$indices[0]]->group_role = 'leader';

            $room_users[$indices[1]]->group_role = 'follower1';
            //$room_users[$indices[1]]->group_id = $room_users[$indices[0]]->group_id;
            $room_users[$indices[2]]->group_role = 'follower2';
            //$room_users[$indices[2]]->group_id = $room_users[$indices[0]]->group_id;
            foreach($room_users as $key=>$room_user){
                $room_user->group_id = $group->id;
                if($room_user->task_id == 0)
                    $room_user->task_id = rand(1,16);
                else
                    $room_user->task_id = (($room_user->task_id + 1) % 16) + 1;
                
                if($room_user->group_role == 'leader'){
                    $room_user->in_room = 0;
                    //$group_task = \Teamwork\GroupTask::firstOrCreate('group_id',$room_user->group_id)->where('name','Cryptography')->first();
                    \Teamwork\GroupTask::initializeMemoryTasks($group->id,$randomize=false);
                    $group_task = \Teamwork\GroupTask::where('group_id',$group->id)->where('name','Memory')->orderBy('created_at','DESC')->first();
                    $group_task->task_id = $room_user->task_id;
                    $group_task->save();
                    event(new SendToTask($room_user));
                }
                else{
                    event(new SendToTask($room_user));
                }
                $room_user->save();
                
            }
            
            return redirect('/task-room/memory');
        }

        $this_group = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('name','Memory')->orderBy('created_at','DESC')->first();

        if($this_group->started && $group_task->completed == 0)
            return redirect('/task-room/memory');

        #sleep(1);

        event(new PlayerJoinedWaitingRoom($this_user));
        return view('layouts.participants.waiting-room')
            ->with('users',$room_users)
            ->with('task',2)
            ->with('PUSHER_APP_KEY',config('app.PUSHER_APP_KEY'));
    }

    public function leaveWaitingRoom(Request $request){
        $user_id = \Auth::user()->id;
        
        $group_name = $request->room_type == '1' ? "Cryptography" : "Memory";

        Log::debug($group_name);
        
        $group_task = \Teamwork\GroupTask::where('name',$group_name)->first();

        $this_user = User::where('id',$user_id)->first();

        event(new PlayerLeftWaitingRoom(User::find($user_id)->participant_id));
        
        $this_user->in_room = 0;

        $this_user->save();

        

        return '200';
    }

    public function stillHere(Request $request){
        $user_id = \Auth::user()->id;

        $this_user = User::where('id',$user_id)->first();

        $this_user->touch();

        $room_users = User::where('in_room',1)->where('status','Active')->where('id','!=',1)->get();

        foreach($room_users as $key => $room_user) {

            $diff = $room_user->updated_at->diffInSeconds(\Carbon\Carbon::now());
            if($diff > 5){
                //$room_user->in_room = 0;
                $room_user->status = 'Inactive';
                $room_user->save();
                event(new StatusChanged($room_user));
                //event(new PlayerLeftWaitingRoom($room_user->participant_id));
            }
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

    public function adminMenu(Request $request){
        return view('layouts.participants.admin-menu');
    }

    public function historicalData(Request $request){
        $userSessions = Session::orderBy('session_id','desc')->get();


        return view('layouts.participants.historical-data')
                ->with('userSessions',$userSessions);
    }

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
