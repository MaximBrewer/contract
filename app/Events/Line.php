<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use App\Http\Resources\LineResource;
use Illuminate\Queue\SerializesModels;

class Line implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $line;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($line)
    {
        //
        $this->line = is_numeric($line) ? $line : new LineResource($line);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('line');
    }
}
