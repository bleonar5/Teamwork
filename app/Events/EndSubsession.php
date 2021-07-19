<?php

#EVENT FORCES USER TO END CURRENT TASK AND EITHER RETURN TO WAITING ROOM OR PROCEED TO CONCLUSION

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

class EndSubsession implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, int $order)
    {
        $this->user = $user;
        $this->order = $order;
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
        return 'end-subsession';
    }
}