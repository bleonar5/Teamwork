<?php

namespace Teamwork\Http\Controllers;

use Illuminate\Http\Request;
use Teamwork\User;
use Teamwork\Events\SendToTask;
use Teamwork\Events\PlayerJoinedWaitingRoom;
use Teamwork\Events\PlayerLeftWaitingRoom;
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

        event(new PlayerJoinedWaitingRoom($this_user));

        Log::debug($room_users);
        Log::debug($all_users);

        return '200';
    }

    public function getWaitingRoom(Request $request){
        $user_id = \Auth::user()->id;

        $this_user = User::where('id',$user_id)->first();

        $this_user->in_room = 1;
        $this_user->save();

        $room_users = User::where('in_room',1)->get();

        $indices = [0,1,2];
        shuffle($indices);
        $assignments = ['leader','follower1','follower2'];
        if(count($room_users) == 3){
            $room_users[$indices[0]]->group_role = 'leader';

            $room_users[$indices[1]]->group_role = 'follower1';
            //$room_users[$indices[1]]->group_id = $room_users[$indices[0]]->group_id;
            $room_users[$indices[2]]->group_role = 'follower2';
            //$room_users[$indices[2]]->group_id = $room_users[$indices[0]]->group_id;
            foreach($room_users as $key=>$room_user){
                $room_user->group_id = $room_users[$indices[0]]->group_id;
                if($room_user->task_id == 0)
                    $room_user->task_id = rand(1,16);
                else
                    $room_user->task_id = (($room_user->task_id + 1) % 16) + 1;
                
                if($room_user->group_role == 'leader'){
                    $room_user->in_room = 0;
                    //$group_task = \Teamwork\GroupTask::firstOrCreate('group_id',$room_user->group_id)->where('name','Cryptography')->first();
                    \Teamwork\GroupTask::initializeCryptoTasks($room_user->group_id,$randomize=false);
                    $group_task = \Teamwork\GroupTask::where('group_id',$room_user->group_id)->where('name','Cryptography')->orderBy('created_at','DESC')->first();
                    $group_task->task_id = $room_user->task_id;
                    $group_task->save();
                }
                else{
                    event(new SendToTask($room_user));
                }
                $room_user->save();
                
            }
            return redirect('/task-room');
        }

        $this_group = \Teamwork\GroupTask::where('group_id',$this_user->group_id)->where('name','Cryptography')->orderBy('created_at','DESC')->first();

        if($this_group->started == 1)
            return redirect('/task-room');

        
        event(new PlayerJoinedWaitingRoom($this_user));
        return view('layouts.participants.waiting-room')->with('users',$room_users);
    }

    public function leaveWaitingRoom(Request $request){
        $user_id = \Auth::user()->id;

        $this_user = User::where('id',$user_id)->first();

        $this_user->in_room = 0;

        $this_user->save();

        event(new PlayerLeftWaitingRoom($this_user));

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
