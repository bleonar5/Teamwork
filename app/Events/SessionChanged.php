<?php

namespace Teamwork\Events;

use Teamwork\User;
use Teamwork\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class SessionChanged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $session;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['my-channel'];//return new PrivateChannel('user.'.$this->user->id);
    }

    public function broadcastAs()
    {
        return 'session-changed';
    }
}