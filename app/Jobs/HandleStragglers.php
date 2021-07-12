<?php

#JOB TO ADD CONCLUSION TASK TO STRAGGLERS AND SEND THEM ALONG

namespace Teamwork\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Teamwork\User;
use Teamwork\Group;
use Teamwork\Events\TaskComplete;
use Teamwork\Events\EndSubsession;
use Teamwork\Events\SendToTask;
use Teamwork\Session;
use Illuminate\Support\Facades\Log;


class HandleStragglers implements ShouldQueue
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
        $in_room = User::where('in_room',1)->where('id','!=',1)->where('status','Active')->get();

        $group = new Group;
        $group->save();

        \Teamwork\GroupTask::initializeConclusionTasks($group->id,$randomize=false);

        foreach($in_room as $key => $straggler){
            $straggler->group_id = $group->id;
            $straggler->save();

            event(new SendToTask($straggler));
        }


    }
}
