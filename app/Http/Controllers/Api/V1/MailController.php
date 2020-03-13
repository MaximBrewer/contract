<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Auction;
use App\Mail\AuctionMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class MailController extends Controller
{
    //
    public function send(Request $r){
        if((int)$r->post('auction') && (string)$r->post('message')){
            $auction = Auction::findOrFail((int)$r->post('auction'));
            switch((int)$r->post('whom')){
                case 0:
                    $users = DB::table('users')->whereRaw('id in (select user_id from user_contragent where contragent_id in (select id from `contragent_auction` WHERE `auction_id`=?))', [$auction->id])->get();
                break;
                case 1:
                    $users = DB::table('users')->whereRaw('id in (select user_id from user_contragent where contragent_id in (select id from `contragent_auction` WHERE `observer`=1 and `auction_id`=?))', [$auction->id])->get();
                break;
                case 2:
                    $users = DB::table('users')->whereRaw('id in (select user_id from user_contragent where contragent_id in (select id from `contragent_auction` WHERE `observer`=1 and `can_bet`=1 and `auction_id`=?))', [$auction->id])->get();
                break;
            }
            foreach($users as $user){
                Mail::to($user)->send(new AuctionMail((string)$r->post('message')));
            }
        } else {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }
    }
}
