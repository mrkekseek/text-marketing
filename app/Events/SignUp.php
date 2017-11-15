<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class SignUp
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $owner;
    public $config;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $owner)
    {
        $this->user = $user;
        $this->owner = $owner;
        $this->config = config('app');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
