<?php

#EVENT FOR UPDATING GROUP CRYPTO PAGE WHEN TEAMMATES TAKE THEIR TURN

namespace Teamwork\Events;

use Teamwork\User;
use Teamwork\GroupTask;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ActionSubmitted implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group_task;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GroupTask $group_task)
    {
        $this->group_task = $group_task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['task-channel'];
    }

    public function broadcastAs()
    {
        return 'action-submitted';
    }
}