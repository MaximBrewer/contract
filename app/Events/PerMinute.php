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
use Illuminate\Support\Facades\DB;

class PerMinute implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $time;
    public $started;
    public $finished;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $carbon = new Carbon();
        $finished = DB::select(
            'select id from auctions where time(finish_at) between time(?) and time(?)',
            [
                $carbon->subMinute()->toDateTimeString(),
                $carbon->addMinute()->toDateTimeString()
            ]
        );

        DB::table('auctions')->whereIn('id', $finished)->update(array(
            'finished' => true,
        ));

        $started = DB::select(
            'select id from auctions where time(start_at) between time(?) and time(?) and confirmed = 1',
            [
                $carbon->subMinute()->toDateTimeString(),
                $carbon->addMinute()->toDateTimeString()
            ]
        );

        foreach($started as $auction){
            DB::table('auctions')->where('id', $auction->id)->update(array(
                'started' => 1,
            ));
        }


        $this->started = $started;
        $this->finished = $finished;
        $this->time = Carbon::now()->format(DATE_ATOM);

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
