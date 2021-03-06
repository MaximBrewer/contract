<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Message;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Auction;
use App\Mail\ChatMessage;
use \App\Events\MessagePushed;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Message as MessageResource;
use App\Http\Resources\Messages as MessagesCollection;

class MessagesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return false;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            "message" => "required",
            "auction_id" => "exists:auctions,id"
        ]);

        $validator->validate();

        $message = Message::create([
            "user_id" => Auth::user()->id,
            "auction_id" => $request->post('auction_id'),
            "contragent_id" => User::find(Auth::user()->id)->contragents[0]->id,
            "username" => User::find(Auth::user()->id)->contragents[0]->title,
            "message" => $request->post('message')
        ]);


        $auction = Auction::findOrFail($request->post('auction_id'));

        $userc = DB::table('user_contragent')->where('contragent_id', $auction->contragent_id)->select('user_id')->get();

        foreach ($userc as $userd) {
            $user = User::find($userd->user_id);
            if ($user)
                Mail::to($user->email)->send(new ChatMessage($request->post('message'), $auction));
        }


        event(new MessagePushed($auction));

        return $message;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return null;
    }


    public function update(Request $request, $id)
    {

        $message = Message::findorFail($id);

        if ($message->user_id != Auth::user()->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            "message" => "required|min:10",
            "auction_id" => "exists:auctions"
        ]);

        $validator->validate();

        $message->update([
            "message" => $request->post('message')
        ]);


        $auction = Auction::findOrFail($message->auction_id);
        event(new MessagePushed($auction));

        return $message;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $message = Message::findorFail($id);

        if ($message->user_id != Auth::user()->id) {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }

        $auction_id = $message->auction_id;

        $message->delete();

        $auction = Auction::findOrFail($auction_id);
        event(new MessagePushed($auction));

        return '';
    }
}
