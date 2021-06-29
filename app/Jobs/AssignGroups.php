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

#JOB THAT MANAGES ASSIGNING USERS TO GROUPS FOR THEIR SUBSESSIONS
#ALSO SCHEDULES EVENTS TO HANDLE SENDING USERS TO TASK, RETURN THEM TO WAITING ROOM, AND SIGNAL ADMIN-PAGE UPDATES
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
        #ITERATE CURRENT_SESSION, NEW SUBSESSION HAS BEGUN
        $admin = User::where('id',1)->first();
        $admin->current_session += 1;
        $admin->save();
        
        #SAVE CURRENT TIME SO ALL USER-SESSIONS HAVE A SHARED CREATED_AT VALUE (SO WE CAN GROUP THEM UP)
        $created_at = \Carbon\Carbon::now();

        //LOOP UNTIL < 3 USERS IN WAITING ROOM ARE UNASSIGNED
        while(true){
            //ONLY CRYPTO FOR NOW
            $task = 1;
            $task_name = "Cryptography";

            //GET ACTIVE WAITING ROOM MEMBERS
            $in_room = User::where('in_room',1)->where('id','!=',1)->where('status','Active')->get()->shuffle();

            if(count($in_room) >= 3){
                //RANDOMLY ASSIGN ROLES FOR NOW
                $leader = $in_room[0];
                $leader->group_role = 'leader';
                $follower1 = $in_room[1];
                $follower1->group_role = 'follower1';
                $follower2 = $in_room[2];
                $follower2->group_role = 'follower2';

                #CREATE NEW GROUP ENTRY
                $group = new Group;
                $group->save();

                #LINK MEMBERS TO NEW GROUP
                $leader->group_id = $group->id;
                $follower1->group_id = $group->id;
                $follower2->group_id = $group->id;

                foreach([$leader,$follower1,$follower2] as $user){
                    //CREATES CROSS-TABLE LINK FROM USER TO GROUP
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

                    //SETS 'TASK_ID', WHICH DEFINES THE SET OF RULES FOR THE CRYPTO ROUND
                    //SET RANDOMLY AT FIRST, THEN ITERATES ONE BY ONE THROUGH THE 16 OPTIONS
                    if($user->task_id == 0)
                        $user->task_id = rand(1,16);
                    else
                        $user->task_id = (($user->task_id + 1) % 16) + 1;

                }
                
                //ASSIGN APPROPRIATE TASKS TO THE GROUP
                if($task == 1){
                    \Teamwork\GroupTask::initializeCryptoTasks($group->id,$randomize=false,$final=$admin->current_session == $admin->max_sessions);
                }
                else{
                    \Teamwork\GroupTask::initializeMemoryTasks($group->id,$randomize=false);
                }

                //LINK GROUP TO TASK_ID, AKA SET OF RULES
                $group_task = \Teamwork\GroupTask::where('group_id',$group->id)->where('name',$task_name)->orderBy('order','ASC')->first();
                $group_task->task_id = $leader->task_id;
                $group_task->save();

                //REMOVE USERS IN GROUP FROM WAITING ROOM
                $leader->in_room = 0;
                $follower1->in_room = 0;
                $follower2->in_room = 0;
                $leader->save();
                $follower1->save();
                $follower2->save();

                
                //IF THIS IS THE FIRST SUBSESSION, THEN WE WILL CREATE AN ENTRY
                //IN THE 'SESSIONS' TABLE FOR EACH USER IN THE GROUP
                //THESE RECORDS ARE DISPLAYED ON THE HISTORICAL DATA PAGE
                //EACH USER WHO PARTICIPATED IN AT LEAST ONE SUBSESSION WILL BE RECORDED
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


                    //IN ORDER TO SHOW THAT ALL OF THESE USERS IN THE VARIOUS GROUPS
                    //ARE PART OF THE SAME OVERARCHING SESSION, WE USE THE STATIC 'CREATED_AT' TIMESTAMP 
                    //(THAT WE DEFINED AT THE TOP OF THIS JOB AND USED TO DEFINE THE 'CREATED_AT' VALUES ALL OUR 'SESSION' TABLE ENTRIES)
                    //WE USE THIS SHARED CREATED_AT VALUE TO ASSIGN A SIMPLIFIED "SESSION_ID" DENOMINATION FOR THE ENTRIES
                    $sessions = Session::orderBy('created_at')->get();
                    $count = 0;
                    $created_at = null;

                    //LOOP THROUGH SESSION ENTRIES AND DEFINE SESSION_ID
                    foreach($sessions as $key => $session){

                        if($session->created_at != $created_at){

                            $count += 1;
                            $created_at = $session->created_at;

                        }
                        if(is_null($session->session_id)){

                            $session->session_id = $count;
                            $session->save();

                        }

                    }

                    $count += 1;
                }
                else {
                    //IF THIS IS NOT THE FIRST SUBSESSION OF THE SESSION
                    //WE HAVE TO ACCOUNT FOR THE POSSIBILITY THAT ONE OF THE GROUP MEMBERS HERE 
                    //DID NOT GET ASSIGNED TO GROUP IN PREVIOUS SUB-SESSIONS

                    //ASSUMING THAT AT LEAST ONE OF THE GROUP MEMBERS WAS ASSIGNED PREVIOUSLY,
                    //WE CAN GRAB THE FRESHEST SESSION ENTRY FOR THE GROUP TO FIND THE SESSION_ID
                    //(WHICH WE WILL USE WHEN WE HAVE TO CREATE A NEW ENTRY FOR THE "NEW" USER)
                    $group_session = Session::whereIn('participant_id',array($leader->participant_id,$follower1->participant_id,$follower2->participant_id))->orderBy('created_at','desc')->first();

                    $leader_session = Session::where('participant_id',$leader->participant_id)->orderBy('created_at','desc')->where('session_id',$group_session->session_id)->first();
                    
                    //IF LEADER WAS NOT ASSIGNED PREVIOUSLY, CREATE ENTRY FOR LEADER
                    if(!$leader_session){
                        $leader_session = new Session;
                        $leader_session->participant_id = $leader->participant_id;
                        $leader_session->type = $task_name;
                        $leader_session->num_subsessions = 1;
                        $leader_session->total_sessions = Session::where('participant_id',$leader->participant_id)->count() + 1;
                        $leader_session->group_ids = (String) $leader->group_id;
                        $leader_session->group_role = $leader->group_role;
                        $leader_session->created_at = $created_at;
                        $leader_session->session_id = $group_session->session_id;
                        $leader_session->save(['timestamps' => 'false']);


                    }
                    //ELSE, UPDATE INFO FOR THEIR ENTRY
                	else{
                        $leader_session->num_subsessions = $leader_session->num_subsessions + 1;
                	    $leader_session->group_ids = $leader_session->group_ids.','.$leader->group_id;
                	    $leader_session->save();
                    }

                	$follower1_session = Session::where('participant_id',$follower1->participant_id)->orderBy('created_at','desc')->where('session_id',$group_session->session_id)->first();

                    //IF FOLLOWER1 WAS NOT ASSIGNED PREVIOUSLY, CREATE ENTRY FOR FOLLOWER1
                    if(!$follower1_session){
                        $follower1_session = new Session;
                        $follower1_session->participant_id = $follower1->participant_id;
                        $follower1_session->type = $task_name;
                        $follower1_session->num_subsessions = 1;
                        $follower1_session->total_sessions = Session::where('participant_id',$follower1->participant_id)->count() + 1;
                        $follower1_session->group_ids = (String) $follower1->group_id;
                        $follower1_session->group_role = $follower1->group_role;
                        $follower1_session->created_at = $created_at;
                        $follower1_session->session_id = $group_session->session_id;
                        $follower1_session->save(['timestamps' => 'false']);
                        

                    }
                    //ELSE, UPDATE INFO FOR FOLLOWER1
                    else{
                        $follower1_session->num_subsessions = $follower1_session->num_subsessions + 1;
                        $follower1_session->group_ids = $follower1_session->group_ids.','.$follower1->group_id;
                        $follower1_session->save();
                    }
                	

                	$follower2_session = Session::where('participant_id',$follower2->participant_id)->orderBy('created_at','desc')->where('session_id',$group_session->session_id)->first();

                    //IF FOLLOWER2 WAS NOT ASSIGNED PREVIOUSLY, CREATE ENTRY FOR FOLLOWER2
                    if(!$follower2_session){
                        $follower2_session = new Session;
                        $follower2_session->participant_id = $follower2->participant_id;
                        $follower2_session->type = $task_name;
                        $follower2_session->num_subsessions = 1;
                        $follower2_session->total_sessions = Session::where('participant_id',$follower2->participant_id)->count() + 1;
                        $follower2_session->group_ids = (String) $follower2->group_id;
                        $follower2_session->group_role = $follower2->group_role;
                        $follower2_session->created_at = $created_at;
                        $follower2_session->session_id = $group_session->session_id;
                        $follower2_session->save(['timestamps' => 'false']);
                    }
                    //ELSE, UPDATE INFO FOR FOLLOWER2
                    else{
                        $follower2_session->num_subsessions = $follower2_session->num_subsessions + 1;
                        $follower2_session->group_ids = $follower2_session->group_ids.','.$follower2->group_id;
                        $follower2_session->save();
                    }
                	
                }

                //SETTING TIME VARIABLES FOR COUNTDOWNS
                $session_start = \Teamwork\Time::where('type','session')->orderBy('created_at','desc')->first();

                $time_elapsed = $session_start->created_at->diffInSeconds(\Carbon\Carbon::now());
           
                $session_length = 120;

                //IMMEDIATELY FIRES EVENTS TO SEND USERS FROM WAITING ROOM TO THEIR NEWLY ASSIGNED TASK
                event(new SendToTask($leader));
                event(new SendToTask($follower1));
                event(new SendToTask($follower2));

                //DISPATCHES DELAYED JOBS WHICH WILL FIRE EVENTS THAT SEND USERS FROM THE TASK TO THE WAITING ROOM/CONCLUSION 
                //AT THE END OF A SUBSESSION
                (new SendTaskComplete($leader->id))->dispatch($leader->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                (new SendTaskComplete($follower1->id))->dispatch($follower1->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));
                (new SendTaskComplete($follower2->id))->dispatch($follower2->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length-30));

                //IF THIS IS THE LAST SUBSESSION, DISPATCH A DELAYED JOB TO FIRE "END OF SESSION" EVENTS
                if($admin->current_session == $admin->max_sessions)
                	(new SendSessionComplete($follower2->id))->dispatch($follower2->id)->delay(\Carbon\Carbon::now()->addSeconds($session_length));

            }
            //IF THERE'S NOT ENOUGH USERS IN THE WAITING ROOM LEFT 
            //FOR ANOTHER GROUP, THEN JOB IS DONE.
            else{
                return '200';
            }

        }
    }
}
