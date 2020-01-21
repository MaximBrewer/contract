<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Comment;
use App\CommentSpam;
use App\CommentVote;
use App\User;
use App\Contragent;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
            'contragent_id' => 'required'
        ]);

        $comment = Comment::where('contragent_id', $request->post('contragent_id'))->where('writer', User::findOrFail(Auth::user()->id)->contragents[0]->id)->first();

        if ($comment)
            $comment->update([
                'comment' => $request->post('comment'),
                'votes' => (int) $request->post('rate'),
            ]);
        else
            $comment = Comment::create([
                'contragent_id' => $request->post('contragent_id'),
                'comment' => $request->post('comment'),
                'votes' => (int) $request->post('rate'),
                'writer' => User::findOrFail(Auth::user()->id)->contragents[0]->id,
            ]);
        if ($comment) {
            $rate = DB::select('update contragents set rating = (select (sum(votes)/count(id)) as total from comments where contragent_id = ?) where id = ?', [$request->post('contragent_id'), $request->post('contragent_id')]);
            $comments = Comment::where('contragent_id', $request->post('contragent_id'))->orderBy('updated_at', 'desc')->get();
            return [
                $comments,
                Contragent::find($request->post('contragent_id'))->rating,
                Comment::where('contragent_id', $request->post('contragent_id'))->where('writer', User::find(Auth::user()->id)->contragents[0]->id)->first()
            ];
        }
    }

    /**
     * Get Comments for contragentId

     * @return Comments
     */

    public function show($contragentId)
    {

        $comments = Comment::where('contragent_id', $contragentId)->get();
        return [
            $comments,
            Contragent::find($contragentId)->rating,
            Comment::where('contragent_id', $contragentId)->where('writer', User::find(Auth::user()->id)->contragents[0]->id)->first()
        ];
    }
}
