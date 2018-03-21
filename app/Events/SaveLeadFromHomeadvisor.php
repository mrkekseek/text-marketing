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

class SaveLeadFromHomeadvisor
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $client;
    public $lead_exists;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $client, $lead_exists)
    {
        $this->user = $user;
        $this->client = $client;
        $this->lead_exists = $lead_exists;
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
