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
use App\Homeadvisor;

class FirstLead
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $owner;
    public $ha;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $owner, Homeadvisor $ha)
    {
        $this->owner = $owner;
        $this->user = $user;
        $this->ha = $ha;
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
