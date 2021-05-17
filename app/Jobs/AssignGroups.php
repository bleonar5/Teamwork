<?php

namespace Teamwork\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Teamwork\User;
use Teamwork\Group;
use Teamwork\Events\SendToTask;
use Teamwork\Events\EndSubsession;
use Teamwork\Jobs\SendTaskComplete;
use Teamwork\Session;
use Illuminate\Support\Facades\Log;


class AssignGroups implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(String $id){
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admin = User::where('id',1)->first();
        $admin->current_session += 1;
        $admin->save();
        
        
        $created_at = \Carbon\Carbon::now();

        while(true){
            $task = 1;
            $task_name = "Cryptography";
            $in_room = User::where('in_room',1)->where('id','!=',1)->get()->shuffle();
            #$in_room = (array) $in_room;
            Log::debug('assign');
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

                

                if($admin->current_session == 1){
                	$leader_session = new Session;
                	$leader_session->participant_id = $leader->participant_id;
                	$leader_session->type = $task_name;
                	$leader_session->num_subsessions = 1;
                	$leader_session->total_sessions = Session::where('participant_id',$leader->participant_id)->count() + 1;
                	$leader_session->group_ids = (String) $leader->group_id;
                	$leader_session->group_role = $leader->group_role;
                	$leader_session->created_at = $created_at;
                	$leader_session->save(['timestamps' => 'false']);

                	$follower1_session = new Session;
                	$follower1_session->participant_id = $follower1->participant_id;
                	$follower1_session->type = $task_name;
                	$follower1_session->num_subsessions = 1;
                	$follower1_session->total_sessions = Session::where('participant_id',$follower1->participant_id)->count() + 1;
                	$follower1_session->group_ids = (String) $follower1->group_id;
                	$follower1_session->group_role = $follower1->group_role;
                	$follower1_session->created_at = $created_at;
                	$follower1_session->save(['timestamps' => 'false']);

                	$follower2_session = new Session;
                	$follower2_session->participant_id = $follower2->participant_id;
                	$follower2_session->type = $task_name;
                	$follower2_session->num_subsessions = 1;
                	$follower2_session->total_sessions = Session::where('participant_id',$follower2->participant_id)->count() + 1;
                	$follower2_session->group_ids = (String) $follower2->group_id;
                	$follower2_session->group_role = $follower2->group_role;
                	$follower2_session->created_at = $created_at;
                	$follower2_session->save(['timestamps' => 'false']);
                }
                else{
                	$leader_session = Session::where('participant_id',$leader->participant_id)->orderBy('created_at','desc')->first();
                	$leader_session->num_subsessions = $leader_session->num_subsessions + 1;
                	$leader_session->group_ids = $leader_session->group_ids.','.$leader->group_id;
                	$leader_session->save();

                	$follower1_session = Session::where('participant_id',$follower1->participant_id)->orderBy('created_at','desc')->first();
                	$follower1_session->num_subsessions = $follower1_session->num_subsessions + 1;
                	$follower1_session->group_ids = $follower1_session->group_ids.','.$follower2->group_id;
                	$follower1_session->save();

                	$follower2_session = Session::where('participant_id',$follower2->participant_id)->orderBy('created_at','desc')->first();
                	$follower2_session->num_subsessions = $follower2_session->num_subsessions + 1;
                	$follower2_session->group_ids = $follower2_session->group_ids.','.$follower2->group_id;
                	$follower2_session->save();
                }
                

                $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

                $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
           
                $session_length = 120;

                event(new SendToTask($leader));
                event(new SendToTask($follower1));
                event(new SendToTask($follower2));

                (new SendTaskComplete($leader->id))->dispatch($leader->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                (new SendTaskComplete($follower1->id))->dispatch($follower1->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                (new SendTaskComplete($follower2->id))->dispatch($follower2->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));

                if($admin->current_session == $admin->max_sessions){
                	(new SendSessionComplete($follower2->id))->dispatch($follower2->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length));
                }

                #(new SendTaskComplete($leader->id))->dispatch($leader->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                #(new SendTaskComplete($follower1->id))->dispatch($follower1->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                #(new SendTaskComplete($follower2->id))->dispatch($follower2->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                /*
                $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

                $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
           
                $session_length = 45;

                $time_remaining = $session_length * $admin->current_session - $time_elapsed;
                $total_time = $session_length * $admin->max_sessions;

                if($time_elapsed >= $total_time){
                    $admin->current_session = NULL;
                    $admin->max_sessions = NULL;
                    $admin->save();

                }*/

                /*while($time_remaining < 0){
                    $admin->current_session += 1;
                    if ($admin->current_session > $admin->max_sessions){
                        $admin->current_session = null;
                        $admin->max_sessions = null;
                        $admin->save();
                        $time_remaining = null;
                        break;
                    }
                     $time_remaining = $session_length * $admin->current_session - $time_elapsed;
                }*/

                $admin->save();

                //event(new SendToTask($leader));
                //event(new SendToTask($follower1));
                //event(new SendToTask($follower2));


                



            }
            else{
                return '200';
            }

        }
    }
}
