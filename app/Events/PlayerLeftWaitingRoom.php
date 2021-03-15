<?php

namespace Teamwork\Events;

use Teamwork\GroupTask;
use Teamwork\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class playerLeftWaitingRoom implements ShouldBroadcast
{
    use SerializesModels;

    public $group_task;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(GroupTask $group_task,User $user)
    {
        $this->group_task = $group_task;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['my-channel'];
    }

    public function broadcastAs()
    {
        return 'player-left-room';
    }
}
