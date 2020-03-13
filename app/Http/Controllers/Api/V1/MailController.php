<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Auction;
use App\Mail\AuctionMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;


class MailController extends Controller
{
    //
    public function send(Request $r){
        if((int)$r->post('auction') && (string)$r->post('message')){
            $auction = Auction::findOrFail((int)$r->post('auction'));
            switch((int)$r->post('whom')){
                case 0:
                break;
                case 1:
                break;
                case 2:
                break;
            }
            // foreach(){
                return [Mail::to('pimax1978@icloud.com')->send(new AuctionMail((string)$r->post('message')))];
            // }
        } else {
            return response()->json([
                'message' => __('It`s not yours!'),
                'errors' => []
            ], 422);
        }
    }
}
