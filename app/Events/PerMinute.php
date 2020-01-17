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
        \DB::connection()->enableQueryLog();
        $auctions = Auction::select('id')->whereBetween(
            \DB::raw('DATE(start_at)'),
            [
                $carbon->subMinute()->toDateTimeString(),
                $carbon->addMinute(3)->toDateTimeString()
            ]
        )->get();
        $queries = \DB::getQueryLog();
        info($queries);
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
