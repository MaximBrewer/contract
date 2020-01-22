<?php

namespace App\Events;

use App\Auction;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
// use Illuminate\Broadcasting\PresenceChannel;
// use Illuminate\Broadcasting\PrivateChannel;
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
    public $auction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $carbon = new Carbon();


        DB::connection()->enableQueryLog();
        
        $finished = DB::select(
            'select id from auctions where timestamp(finish_at) < timestamp(?) and confirmed = 1 and started = 1 and finished = 0',
            [
                $carbon->toDateTimeString()
            ]
        );

        $queries = DB::getQueryLog();
        info($queries);

        foreach($finished as $auction){
            DB::table('auctions')->where('id', $auction->id)->update(array(
                'finished' => 1,
            ));
            event(new MessagePushed(Auction::find($auction->id)));
        }

        $started = DB::select(
            'select id from auctions where timestamp(start_at) < timestamp(?) and confirmed = 1 and started = 0',
            [
                $carbon->toDateTimeString()
            ]
        );

        foreach($started as $auction){
            DB::table('auctions')->where('id', $auction->id)->update(array(
                'started' => 1,
            ));
            event(new MessagePushed(Auction::find($auction->id)));
        }


        $this->started = $started;
        $this->finished = $finished;
        $this->time = $carbon->format(DATE_ATOM);

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
