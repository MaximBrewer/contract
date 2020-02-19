<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Auction;
use \App\Bet;
use \App\User;
use Illuminate\Support\Facades\Auth;
use \App\Contragent;
use \App\Target;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\Store;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AuctionResource;


class AuctionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $action = null)
    {

        switch ($action) {
            case "all":
                return AuctionResource::collection(Auction::where('finished', '<>', 1)->orderBy('id', 'desc')->get());
                break;
            case "archive":
                return AuctionResource::collection(Auction::where('finished', 1)->orderBy('id', 'desc')->get());
                break;
            case "my":
                return AuctionResource::collection(Auction::orderBy('id', 'desc')->where('finished', '<>', 1)->where('contragent_id', Auth::user()->contragents[0]->id)->get());
                break;
            case "bid":
                return AuctionResource::collection(Auth::user()->contragents[0]->auctions);
                break;
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function archive()
    {

        return Auction::where('finished', 1)->orderBy('id', 'desc')->get();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function toggleBidder(Request $r)
    {

        $can_bet = $r->post('can_bet');
        $observer = $r->post('observer') || $can_bet ? true : false;

        \App\ContragentAuction::where('auction_id', $r->post('auction_id'))->where('contragent_id', $r->post('contragent_id'))->update(['can_bet' => $can_bet, 'observer' => $observer]);

        $auction = Auction::findOrFail($r->post('auction_id'));

        if (!$can_bet) {
            Bet::where('auction_id', $r->post('auction_id'))->where('contragent_id', $r->post('contragent_id'))->delete();
        }


        if ($auction) event(new \App\Events\MessagePushed($auction));

        return 1;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeManager(Request $request)
    {


        $request->validate([
            "multiplicity.id" => "required|exists:multiplicities,id",
            "product.id" => "required|exists:products,id",
            "store.id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "start_price" => "required",
            "volume" => "required",
            "step" => "required",
        ]);


        $auction = Auction::create([
            'contragent_id' => $request->post('contragent')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'product_id' => $request->post('product')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
            'step' => $request->post('step'),
        ]);

        $auction = Auction::findOrFail($auction->id);
        return $auction;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            "multiplicity.id" => "required|exists:multiplicities,id",
            "product.id" => "required|exists:products,id",
            "store.id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "start_price" => "required|regex:~[0-9\.\,]*~",
            "volume" => "required|regex:~[0-9\.\,]*~",
            "step" => "required",
        ]);

        $auction = Auction::create([
            'contragent_id' => Auth::user()->contragents[0]->id,
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'product_id' => $request->post('product')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
            'step' => $request->post('step'),
        ]);

        $auction = Auction::findOrFail($auction->id);
        return $auction;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find(Auth::user()->id);
        Bet::where('contragent_id', $user->contragents[0])->update([
            'took_part' => Carbon::now()
        ]);
        return Auction::findOrFail($id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function copy(Request $r)
    {
        $auction = Auction::findOrFail($r->post('id'));

        if ($auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $bidders = $auction->bidders;
        $auction = $auction->toArray();

        unset($auction['id']);
        unset($auction['created_at']);
        unset($auction['updated_at']);
        unset($auction['finish_at']);
        unset($auction['start_at']);
        unset($auction['started']);
        unset($auction['finished']);
        unset($auction['confirmed']);

        $new_auction = Auction::create($auction);

        foreach ($bidders as $bidder) {

            Contragent::find($bidder['id'])->auctions()->attach($new_auction->id);
        }

        return $new_auction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bid(Request $request, $action, $id)
    {

        if (count(Auth::user()->contragents)) Auth::user()->contragents[0]->auctions()->attach($id);

        $auction = Auction::findOrFail($id);
        if ($auction) event(new \App\Events\MessagePushed($auction));

        return $this->index($request, $action);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unbid(Request $request, $action, $id)
    {
        if (count(Auth::user()->contragents)) Auth::user()->contragents[0]->auctions()->detach($id);

        $auction = Auction::findOrFail($id);
        if ($auction) event(new \App\Events\MessagePushed($auction));

        return $this->index($request, $action);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $auction = Auction::findOrFail($id);

        if ($auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $request->validate([
            "multiplicity.id" => "required|exists:multiplicities,id",
            "product.id" => "required|exists:products,id",
            "store.id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "start_price" => "required|regex:~[0-9\.\,]*~",
            "volume" => "required|regex:~[0-9\.\,]*~",
            "step" => "required",
        ]);

        $auction->update([
            'product_id' => $request->post('product')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
            'step' => $request->post('step'),
        ]);

        if ($auction) event(new \App\Events\MessagePushed($auction));

        return $auction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateManager(Request $request, $id)
    {
        $auction = Auction::findOrFail($id);
        $auction->update([
            'contragent_id' => $request->post('contragent')['id'],
            'product_id' => $request->post('product')['id'],
            'multiplicity_id' => $request->post('multiplicity')['id'],
            'store_id' => $request->post('store')['id'],
            'start_at' => date('Y-m-d H:i:s', strtotime($request->post('start_at'))),
            'finish_at' => date('Y-m-d H:i:s', strtotime($request->post('finish_at'))),
            'comment' => $request->post('comment'),
            'start_price' => $request->post('start_price'),
            'volume' => $request->post('volume'),
        ]);
        $auction = Auction::findOrFail($id);
        return $auction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addBidder(Request $request)
    {
        foreach ($request->post('bidders') as $bidder) {
            $auction = Auction::findOrFail($request->post('auction'));
            $contragent = Contragent::findOrFail($bidder['id']);
            $contragent->auctions()->attach($auction->id);
        }
        $auction = Auction::findOrFail($request->post('auction'));

        if ($auction) event(new \App\Events\MessagePushed($auction));

        return $auction;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $timestamp = Carbon::now()->timestamp;

        $auction = Auction::findOrFail($id);


        if ($auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if (strtotime($auction->finish_at) >= $timestamp) {
            if (strtotime($auction->start_at) <= $timestamp)
                $auction->update([
                    'started' => 1,
                    'confirmed' => 1,
                ]);
            else
                $auction->update([
                    'confirmed' => 1,
                ]);
        }

        $auction = Auction::findOrFail($id);
        if ($auction) event(new \App\Events\MessagePushed($auction));

        return $auction;
    }

    public function betRemove($id)
    {
        $bet = Bet::findOrFail($id);
        $auction = $bet->auction;

        if (empty($auction) || $auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if ($bet->approved_volume || $bet->approved_contract) {
            return response()->json([
                'message' => __('Bet already approved!'),
                'errors' => []
            ], 422);
        }

        $bet->delete();

        $auction = Auction::findOrFail($auction->id);

        event(new \App\Events\MessagePushed($auction));

        return ['ok'];
    }

    public function approveContract(Request $r)
    {

        $bet = Bet::findOrFail($r->id);
        $auction = $bet->auction;



        if (empty($auction) || $auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if (!$bet->approved_volume) {
            return response()->json([
                'message' => __('Approve volume!'),
                'errors' => []
            ], 422);
        }

        $bet->update([
            'approved_contract' => Carbon::now(),
            'correct' => (float) $r->correct
        ]);

        $auction = Auction::findOrFail($auction->id);

        event(new \App\Events\MessagePushed($auction));

        return ['ok'];
    }

    public function approveVolume($id)
    {
        $bet = Bet::findOrFail($id);
        $auction = $bet->auction;


        if (empty($auction) || $auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $bet->update([
            'approved_volume' => Carbon::now()
        ]);

        $auction = Auction::findOrFail($auction->id);

        $auction->update([
            'volume' => ($auction->volume - $bet->volume)
        ]);

        if (!$auction->volume)
            $auction->update([
                'finish_at' => Carbon::now(),
                'finished' => 1
            ]);

        event(new \App\Events\MessagePushed($auction));

        return ['ok'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bet(Request $r)
    {


        if (!(float) $r->post('price')) {
            return response()->json([
                'message' => __('Input price!'),
                'errors' => []
            ], 422);
        }

        if (!(int) $r->post('volume')) {
            return response()->json([
                'message' => __('Input volume!'),
                'errors' => []
            ], 422);
        }

        if (!$r->post('store')) {
            return response()->json([
                'message' => __('Choose store!'),
                'errors' => []
            ], 422);
        }

        $auction = DB::select('select id, contragent_id, can_bet from contragent_auction where auction_id = ? && contragent_id = ?', [$r->post('auction'), $r->post('bidder')]);

        if (empty($auction) || $r->post('bidder') != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if (!$auction[0]->can_bet) {
            return response()->json([
                'message' => __('You can`t bet!'),
                'errors' => []
            ], 422);
        }

        $auction = Auction::findOrFail($r->post('auction'));



        if ((float) $r->post('price') < $auction->start_price) {
            return response()->json([
                'message' => __('Ai Ai!'),
                'errors' => []
            ], 422);
        }


        if ((float) $r->post('price') < $auction->start_price) {
            return response()->json([
                'message' => __('Too low price!'),
                'errors' => []
            ], 422);
        }

        $newBet = new Bet([
            'id' => null,
            'auction_id' => $r->post('auction'),
            'contragent_id' => $r->post('bidder'),
            'price' => (float) $r->post('price'),
            'volume' => $r->post('volume'),
            'took_part' => Carbon::now(),
            'store_id' => $r->post('store'),
            'can_bet' => 1
        ]);


        Bet::where('contragent_id', $r->post('bidder'))->update([
            'took_part' => Carbon::now()
        ]);

        $freeVolume = $auction->volume;


        if ($auction->volume < $r->post('volume')) {
            return response()->json([
                'message' => __('You request more than auction total volume!'),
                'errors' => []
            ], 422);
        }
        // DB::connection()->enableQueryLog();

        $auctionBets = Bet::where('auction_id', $r->post('auction'))->where('contragent_id', '<>', $r->post('bidder'))->where(function ($query) {
            $query
                ->whereNull('approved_volume')
                ->orWhere('approved_volume', '<', 1);
        })
            ->orderBy('price', 'desc')
            ->orderBy('volume', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        $myBetsSum = Bet::where('auction_id', $r->post('auction'))->where('contragent_id', $r->post('bidder'))->where(function ($query) {
            $query
                ->whereNull('approved_volume')
                ->orWhere('approved_volume', '<', 1);
        })
            ->sum('volume');


        $freeVolume -= $myBetsSum;


        // $queries = DB::getQueryLog();
        // info($queries);

        $bets = [];
        $nev = false;
        $rft = false;

        foreach ($auctionBets as $auctionBet) {
            if ($newBet->price - $auction->step >= $auctionBet->price && !$rft) {
                $bets[] = $newBet;
                $rft = true;
            }
            $bets[] = $auctionBet;
        }
        if (!$rft) $bets[] = $newBet;

        foreach ($bets as $bet) {

            if ($bet->id && $freeVolume <= 0) {
                Bet::find($bet->id)->delete();
                continue;
            }
            if ($freeVolume >= $bet->volume) {
                $freeVolume -= $bet->volume;
                if (!$bet->id)
                    $newBet->save();
            } elseif ($bet->id) {
                Bet::find($bet->id)->update(['volume' => $freeVolume]);
                $freeVolume = 0;
            } else {
                $nev = true;
            }
        }

        if ($nev) {
            return response()->json([
                'message' => __('Too low price or too much volume!'),
                'errors' => []
            ], 422);
        }

        if ($auction = Auction::find($r->post('auction'))) {
            if($auction->finish_at < Carbon::now()->addMinutes(10))
            $auction->update([
                'finish_at' => $auction->finish_at + 600
            ]);
            event(new \App\Events\MessagePushed($auction));
        }
        return ['ok'];
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auction = Auction::findOrFail($id);

        if ($auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if ($auction->confirmed) {
            return response()->json([
                'message' => __('Auction has confirmed!'),
                'errors' => []
            ], 422);
        }

        $auction->delete();
        return '';
    }
}
