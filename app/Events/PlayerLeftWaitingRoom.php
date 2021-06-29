<?php

#EVENT NOTIFIES ADMIN-PAGE IF USER LEAVES WAITING ROOM

namespace Teamwork\Events;

use Teamwork\GroupTask;
use Teamwork\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class playerLeftWaitingRoom implements ShouldBroadcastNow
{
    use SerializesModels;

    public $participant_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(String $participant_id)
    {
        $this->participant_id = $participant_id;
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
