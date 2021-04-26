<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\Auction;
use \App\Bet;
use \App\User;
use \App\Events\MessagePushed;
use Illuminate\Support\Facades\Auth;
use \App\Contragent;
use \App\History;
use App\Attachment;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use \App\Store;
use App\Http\Resources\AuctionFull as AuctionFullResource;
use \App\Tag;
use Illuminate\Support\Facades\Validator;
use App\ContragentAuction;
use App\Http\Resources\AuctionResource;
use App\Interval;


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
                $auctions = Auction::where('finished', '<>', 1);
                break;
            case "alls":
                $auctions = Auction::where('confirmed', 1)->where('mode', '<>', 'callApp')->where('finished', '<>', 1);
                break;
            case "confirmed":
                $auctions = Auction::where('confirmed', 1)->where('finished', '<>', 1);
                break;
            case "archive":
                $auctions = Auction::where('finished', 1);
                break;
            case "my":
                $auctions = Auction::where('finished', '<>', 1)->where('contragent_id', Auth::user()->contragents[0]->id);
                break;
            case "bid":
                $auctions = Auth::user()->contragents[0]->bid_auctions();
                break;
        }
        if (Auth::user())
            $auctions->where('kind_id', Auth::user()->kind_id);
        return AuctionResource::collection($auctions->orderBy('id', 'desc')->get());
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


        if ($auction) event(new MessagePushed($auction));

        return 1;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $attributes = $request->all();

        $request->validate([
            "multiplicity_id" => "required|exists:multiplicities,id",
            "product_id" => "required|exists:products,id",
            "store_id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "delay_sell" => "numeric|min:0|max:300",
            "delay_buy" => "numeric|min:0|max:300",
            "comment" => "",
            "step" => "required",
            "can_bet" => "required",
            "mode" => "required",
            "intervals.*.start_price" => "required|numeric",
            "intervals.*.volume" => "required|numeric",
            "intervals.*.from" => "required",
            "intervals.*.to" => "required|after_or_equal:intervals.*.from",
        ]);

        $attributes = $request->all();
        $attributes['kind_id'] = Auth::user()->kind_id;


        $rtags = explode(',', strval($attributes['tags']));


        $rtags = array_filter($rtags, function ($element) {
            return !empty($element);
        });


        unset($attributes['tags']);

        $attributes['start_at'] = date('Y-m-d H:i:s', strtotime($attributes['start_at']));
        $attributes['finish_at'] = date('Y-m-d H:i:s', strtotime($attributes['finish_at']));

        $attributes['contragent_id'] = User::find(Auth::user()->id)->contragents[0]->id;
        $auction = Auction::create($attributes);


        $path = 'auctions' . DIRECTORY_SEPARATOR . $auction->id . DIRECTORY_SEPARATOR;
        $fullpath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;

        @mkdir($fullpath, 0777, true);
        foreach (Attachment::where('entity_id', $auction->id)->where('entity', 'auction')->get() as $attachment) {
            if (is_array($request->post('pics')) && !in_array($attachment->id, $request->post('pics')))
                $attachment->delete();
        };
        if ($request->hasfile('images')) {
            $c = 0;
            foreach ($request->file('images') as $file) {
                $extension = $file->getClientOriginalExtension();
                do {
                    $filename = Str::random(20);
                } while (is_file($fullpath . $filename . '.' . $file->getClientOriginalExtension()));
                $file->move($fullpath, $filename . '.' . $extension);
                Attachment::create([
                    'title' => $filename,
                    'url' => $path . $filename . '.' . $extension,
                    'entity' => 'auction',
                    'entity_id' => $auction->id,
                    'sort' => (++$c) * 100,
                ]);
            }
        }

        foreach ($attributes['intervals'] as $data) {
            $data['auction_id'] = $auction->id;
            $data['from'] = date('Y-m-d', strtotime($data['from']));
            $data['to'] = date('Y-m-d', strtotime($data['to']));
            $interval = Interval::create($data);
        }

        $auction->tags()->sync($rtags);

        $auction = Auction::findOrFail($auction->id);
        return new AuctionFullResource($auction);
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

        $attributes = $request->all();

        $request->validate([
            "multiplicity_id" => "required|exists:multiplicities,id",
            "product_id" => "required|exists:products,id",
            "store_id" => "required|exists:stores,id",
            "start_at" => "required",
            "finish_at" => "required|after:start_at",
            "comment" => "",
            "step" => "required",
            "can_bet" => "required",
            "mode" => "required",
            "intervals.*.start_price" => "required|numeric",
            "intervals.*.volume" => "required|numeric",
            "intervals.*.from" => "required",
            "intervals.*.to" => "required|after_or_equal:intervals.*.from",
        ]);

        $rtags = explode(',', strval($attributes['tags']));

        $rtags = array_filter($rtags, function ($element) {
            return !empty($element);
        });

        unset($attributes['tags']);

        $attributes['start_at'] = date('Y-m-d H:i:s', strtotime($attributes['start_at']));
        $attributes['finish_at'] = date('Y-m-d H:i:s', strtotime($attributes['finish_at']));

        $auction->tags()->sync($rtags);
        $auction->update($attributes);

        $ids = [];
        foreach ($attributes['intervals'] as $data) {
            $data['auction_id'] = $auction->id;
            $data['from'] = date('Y-m-d', strtotime($data['from']));
            $data['to'] = date('Y-m-d', strtotime($data['to']));
            if ($data['id']) {
                $interval = Interval::findOrFail($data['id']);
                $interval->update($data);
            } else {
                $interval = Interval::create($data);
            }
            $ids[] = $interval->id;
        }
        Interval::where('auction_id', $auction->id)->whereNotIn('id', $ids)->delete();

        $path = 'auctions' . DIRECTORY_SEPARATOR . $auction->id . DIRECTORY_SEPARATOR;
        $fullpath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;

        @mkdir($fullpath, 0777, true);
        foreach (Attachment::where('entity_id', $auction->id)->where('entity', 'auction')->get() as $attachment) {
            if (is_array($request->post('pics')) && !in_array($attachment->id, $request->post('pics')))
                $attachment->delete();
        };
        if ($request->hasfile('images')) {
            $c = 0;
            foreach ($request->file('images') as $file) {
                $extension = $file->getClientOriginalExtension();
                do {
                    $filename = Str::random(20);
                } while (is_file($fullpath . $filename . '.' . $file->getClientOriginalExtension()));
                $file->move($fullpath, $filename . '.' . $extension);
                Attachment::create([
                    'title' => $filename,
                    'url' => $path . $filename . '.' . $extension,
                    'entity' => 'auction',
                    'entity_id' => $auction->id,
                    'sort' => (++$c) * 100,
                ]);
            }
        }

        if ($auction) event(new MessagePushed($auction));

        $auction = Auction::findOrFail($auction->id);

        return new AuctionFullResource($auction);
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
        Bet::where('contragent_id', $user->contragents[0]->id)->update([
            'took_part' => Carbon::now()
        ]);
        return new AuctionFullResource(Auction::findOrFail($id));
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

        $tags = $auction->tags;
        $auctionArray = $auction->toArray();

        unset($auctionArray['id']);
        unset($auctionArray['created_at']);
        unset($auctionArray['updated_at']);
        unset($auctionArray['finish_at']);
        unset($auctionArray['start_at']);
        unset($auctionArray['started']);
        unset($auctionArray['finished']);
        unset($auctionArray['confirmed']);

        $new_auction = Auction::create($auctionArray);

        foreach ($auction->bidders as $bidder) {
            ContragentAuction::create([
                'contragent_id' => $bidder['id'],
                'auction_id' => $new_auction->id,
                'can_bet' => $bidder['can_bet'],
                'observer' => $bidder['observer'],
            ]);
        }

        $path = 'auctions' . DIRECTORY_SEPARATOR . $new_auction->id . DIRECTORY_SEPARATOR;
        $oldpath = 'auctions' . DIRECTORY_SEPARATOR . $auction->id . DIRECTORY_SEPARATOR;
        $fullpath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;
        $oldfullpath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $oldpath;
        @mkdir($fullpath, 0777, true);

        $attchs = Attachment::where('entity_id', $auction->id)->where('entity', 'auction')->get();

        foreach ($attchs as $attachment) {
            $filename = explode(DIRECTORY_SEPARATOR, $attachment->url);
            $filename = end($filename);
            @copy($oldfullpath . $filename, $fullpath . $filename);
            Attachment::create([
                'title' => $attachment->title,
                'url' => $path . $filename,
                'entity' => 'auction',
                'entity_id' => $new_auction->id,
                'sort' => $attachment->sort,
            ]);
        };

        foreach ($tags as $tag)
            Tag::find($tag['id'])->auctions()->attach($new_auction->id);

        return $new_auction;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bid($id)
    {

        if (count(Auth::user()->contragents)) Auth::user()->contragents[0]->auctions()->attach($id);

        $auction = Auction::findOrFail($id);
        if ($auction) event(new MessagePushed($auction));

        return new AuctionResource($auction);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unbid($id)
    {

        if (Bet::where('auction_id', $id)->where('contragent_id', User::find(Auth::user()->id)->contragents[0]->id)->count())
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);

        if (count(User::find(Auth::user()->id)->contragents)) User::find(Auth::user()->id)->contragents[0]->auctions()->detach($id);

        // Bet::where('auction_id', $id)->where('contragent_id', User::find(Auth::user()->id)->contragents[0]->id)->delete();

        $auction = Auction::findOrFail($id);
        if ($auction) event(new MessagePushed($auction));

        return new AuctionResource($auction);
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
        $auction = Auction::findOrFail($request->post('auction'));
        foreach ($request->post('bidders') as $bidder) {
            //if (User::find(Auth::user()->id)->contragents[0]->id == $bidder['id']) continue;
            if ($auction->contragent_id == $bidder['id'] && $auction->contragent_id == User::find(Auth::user()->id)->contragents[0]->id) continue;
            $contragent = Contragent::findOrFail($bidder['id']);
            $contragent->auctions()->attach($auction->id);
            $contragentAuction = ContragentAuction::where('auction_id', $auction->id)->where('contragent_id', $contragent->id)->first();
            if ($auction->can_bet == 'yes') {
                $contragentAuction->update(['can_bet' => 1, 'observer', 1]);
            }
        }
        $auction = Auction::findOrFail($request->post('auction'));

        if ($auction) event(new MessagePushed($auction));

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
        if ($auction) event(new MessagePushed($auction));

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

        event(new MessagePushed($auction));

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
            'correct' => (float) $r->correct ? (float) $r->correct : $bet->price
        ]);


        $auction = Auction::findOrFail($auction->id);

        event(new MessagePushed($auction));

        return ['ok'];
    }

    public function unApproveContract(Request $r)
    {

        $bet = Bet::findOrFail($r->id);
        $auction = $bet->auction;

        if (empty($auction) || $auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if ($auction->finished) {
            return response()->json([
                'message' => __('Аукцион уже завершен!'),
                'errors' => []
            ], 422);
        }

        if (strtotime($auction->finish_at) - time() - $auction->delay_sell * 60 < 0) {
            return response()->json([
                'message' => __('Уже нельзя отменить бронь!'),
                'errors' => []
            ], 422);
        }

        $bet->update([
            'approved_contract' => null
        ]);


        $auction = Auction::findOrFail($auction->id);

        event(new MessagePushed($auction));

        return ['ok'];
    }

    public function guaranteeBet(Request $r)
    {

        $bet = Bet::findOrFail($r->id);
        $auction = $bet->auction;

        if (empty($auction) || $bet->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $bet->update([
            'guarantee' => !$bet->guarantee
        ]);


        $auction = Auction::find($auction->id);

        event(new MessagePushed($auction));

        return ['bet' => $bet];
    }

    public function self(Request $r)
    {

        $bet = Bet::findOrFail($r->id);
        $auction = $bet->auction;

        if (empty($auction) || $bet->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $bet->update([
            'self' => !$r->self
        ]);


        $auction = Auction::find($auction->id);

        event(new MessagePushed($auction));

        return ['bet' => $bet];
    }

    public function unApproveVolume($id)
    {
        $bet = Bet::findOrFail($id);
        $auction = $bet->auction;


        if (empty($auction) || $auction->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if ($auction->finished) {
            return response()->json([
                'message' => __('Аукцион уже завершен!'),
                'errors' => []
            ], 422);
        }

        if ($bet->approved_contract) {
            return response()->json([
                'message' => __('Сначала уберите утверждение контракта!'),
                'errors' => []
            ], 422);
        }

        if (strtotime($auction->finish_at) - time() - $auction->delay_sell < 0) {
            return response()->json([
                'message' => __('Уже нельзя отменить бронь!'),
                'errors' => []
            ], 422);
        }

        $bet->update([
            'approved_volume' => null
        ]);

        // DB::table('auctions')->where('id', $auction->id)->update([
        //     'volume' => ($auction->volume - $bet->volume)
        // ]);

        $auction = Auction::findOrFail($auction->id);

        // if (!$auction->volume)
        //     $auction->update([
        //         'finish_at' => Carbon::now(),
        //         'finished' => 1
        //     ]);

        event(new MessagePushed($auction));

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

        // DB::table('auctions')->where('id', $auction->id)->update([
        //     'volume' => ($auction->volume - $bet->volume)
        // ]);

        $auction = Auction::findOrFail($auction->id);

        // if (!$auction->volume)
        //     $auction->update([
        //         'finish_at' => Carbon::now(),
        //         'finished' => 1
        //     ]);

        event(new MessagePushed($auction));

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

        if (!(float) $r->post('volume')) {
            return response()->json([
                'message' => __('Input volume!'),
                'errors' => []
            ], 422);
        }

        if ((float) $r->post('volume') <= 0) {
            return response()->json([
                'message' => __('Объем должен быть положительным!'),
                'errors' => []
            ], 422);
        }

        if (!$r->post('store')) {
            return response()->json([
                'message' => __('Choose store!'),
                'errors' => []
            ], 422);
        }

        if (!$r->post('interval')) {
            return response()->json([
                'message' => __('Choose interval!'),
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

        $interval = DB::select('select id from intervals where auction_id = ? && id = ?', [$r->post('auction'), $r->post('interval')]);


        if (empty($interval) || $r->post('bidder') != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $interval = Interval::findOrFail($r->post('interval'));

        if (!$auction[0]->can_bet) {
            return response()->json([
                'message' => __('You can`t bet!'),
                'errors' => []
            ], 422);
        }

        $auction = Auction::findOrFail($r->post('auction'));

        if ((float) $r->post('price') < $interval->start_price) {
            return response()->json([
                'message' => __('Too low price!'),
                'errors' => []
            ], 422);
        }

        $newBet = new Bet([
            'id' => null,
            'auction_id' => $r->post('auction'),
            'interval_id' => $interval->id,
            'contragent_id' => $r->post('bidder'),
            'price' => (float) $r->post('price'),
            'volume' => $r->post('volume'),
            'distributor_id' => $r->post('distributor'),
            'took_part' => Carbon::now(),
            'store_id' => $r->post('store'),
            'can_bet' => 1
        ]);


        Bet::where('contragent_id', $r->post('bidder'))->update([
            'took_part' => Carbon::now()
        ]);

        $freeVolume = $interval->free_volume;


        if ($interval->volume < $r->post('volume')) {
            return response()->json([
                'message' => __('You request more than auction total volume!'),
                'errors' => []
            ], 422);
        }
        // DB::connection()->enableQueryLog();

        $auctionBets = Bet::where('interval_id', $r->post('interval'))
            ->select('bets.*', 'contragents.rating')
            ->where('contragent_id', '<>', $r->post('bidder'))
            ->whereNull('approved_volume')
            ->leftJoin('contragents', 'contragents.id', '=', 'bets.contragent_id')
            ->orderBy('price', 'desc')
            ->orderBy('volume', 'desc')
            ->orderBy('rating', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();

        // $queries = DB::getQueryLog();
        // info($queries);

        $myBetsSum = Bet::where('interval_id', $r->post('interval'))->where('contragent_id', $r->post('bidder'))->where(function ($query) {
            $query
                ->whereNull('approved_volume')
                ->orWhere('approved_volume', '<', 1);
        })
            ->sum('volume');

        $freeVolume -= $myBetsSum;

        $bets = [];
        $nev = false;
        $rft = false;

        foreach ($auctionBets as $auctionBet) {

            if ($newBet->price - $auction->step >= ($auctionBet->price - 0.001) && !$rft) {
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
                if (!$bet->id) {
                    if ($auction->autosale == 'yes') {
                        $newBet->approved_volume = Carbon::now();
                        $newBet->approved_contract = Carbon::now();
                        $newBet->correct = $newBet->price;
                    }
                    $newBet->save();

                    History::create([
                        'auction_id' => $newBet->auction_id,
                        'interval_id' => $newBet->interval_id,
                        'volume' => $newBet->volume,
                        'price' => $newBet->price,
                        'distributor_id' => $newBet->distributor_id,
                        'contragent_id' => $newBet->contragent_id,
                    ]);
                }
            } elseif ($bet->id) {
                Bet::find($bet->id)->update(['volume' => $freeVolume]);
                $freeVolume = 0;
            } else {
                $nev = true;
            }
        }

        if ($nev) {
            return response()->json([
                'message' => __('Too low price, too much volume, or you are trying to outbid your own bet!'),
                'errors' => []
            ], 422);
        }

        if ($auction = Auction::find($r->post('auction'))) {

            if (strtotime($auction->finish_at) < Carbon::now()->addMinutes(10)->timestamp) {
                $auction->update([
                    'finish_at' => date('Y-m-d H:i:s', Carbon::now()->addMinutes(10)->timestamp)
                ]);
            }

            event(new MessagePushed($auction));
        }
        return ['ok'];
    }

    public function unbet($id)
    {
        $bet = Bet::findOrFail($id);
        $auction = $bet->auction;

        if (empty($bet) || $bet->contragent_id != Auth::user()->contragents[0]->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        if ($auction->finished) {
            return response()->json([
                'message' => __('Аукцион уже завершен!'),
                'errors' => []
            ], 422);
        }

        if (strtotime($auction->finish_at) - time() - $auction->delay_buy * 60 < 0) {
            return response()->json([
                'message' => __('До конца торгов Вы не можете удалять свои ставки!'),
                'errors' => []
            ], 422);
        }

        $bet->delete();

        $auction = Auction::findOrFail($auction->id);

        event(new MessagePushed($auction));

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
