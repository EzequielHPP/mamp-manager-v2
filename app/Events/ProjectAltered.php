<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProjectAltered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectId;
    public $settings;

    /**
     * Create a new event instance.
     *
     * @param  int  $projectId
     */
    public function __construct(int $projectId, array $settings)
    {
        $this->projectId = $projectId;
        $this->settings = $settings;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('projects');
    }
}
