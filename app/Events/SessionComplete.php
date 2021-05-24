<?php

namespace Teamwork\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Teamwork\User;
use Teamwork\Events\TaskComplete;
use Teamwork\Events\EndSubsession;
use Illuminate\Support\Facades\Log;


class SessionComplete implements ShouldQueue
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
        $admin->current_session = null;
        $admin->max_sessions = null;
        $admin->save();

        $lingerers = User::where('in_room',1)->where('id','!=',1)->get();
        foreach($lingerers as $key => $user){
            $user->in_room = 0;
            $user->status = 'Inactive';
            $user->save();
        }
    }
}
