<?php

namespace App\Events;

use App\Auction;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PerMinute implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $time;
    public $auctions;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $carbon = new Carbon();
        $auctions = \DB::select(
            'select id from auctions where time(start_at) between time(?) and time(?)',
            [
                $carbon->subMinute()->toDateTimeString(),
                $carbon->addMinute()->toDateTimeString()
            ]
        );
        $this->auctions = $auctions;
        $this->time = $carbon->toDateTimeString();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('every-minute');
    }
}
