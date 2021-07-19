<?php

#THIS JOB FIRES AN EVENT TO REMOVE A USER FROM THEIR TASK AT THE END OF THE SUBSESSION

namespace Teamwork\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Teamwork\User;
use Teamwork\GroupTask;
use Teamwork\Events\TaskComplete;
use Teamwork\Events\EndSubsession;
use Illuminate\Support\Facades\Log;


class SendTaskComplete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(String $id,Integer $order){
        $this->id = $id;
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this_user = User::find($this->id);
        event(new EndSubsession($this_user,$order);
    }
}
