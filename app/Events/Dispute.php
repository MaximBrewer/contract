<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\DisputeResource as DisputeResource;
use Illuminate\Queue\SerializesModels;

class Dispute implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $dispute;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($dispute)
    {
        $this->dispute = is_numeric($dispute) ? $dispute : new DisputeResource($dispute);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('dispute');
    }
}
