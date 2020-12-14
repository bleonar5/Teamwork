<?php

namespace Teamwork\Events;

use Teamwork\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RuleBroken implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user, $rule_broken;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $rule_broken)
    {
        $this->user = $user;
        $this->rule_broken = $rule_broken;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['task-channel'];//return new PrivateChannel('user.'.$this->user->id);
    }

    public function broadcastAs()
    {
        return 'rule-broken';
    }
}