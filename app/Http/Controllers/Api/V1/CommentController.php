<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\CommentSpam;
use App\CommentVote;
use App\User;

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
            'reply_id' => 'filled',
            'contragent_id' => 'filled',
            'user_id' => 'required',
        ]);
        $comment = Comment::create($request->all());
        if ($comment)
            return ["status" => "true", "commentId" => $comment->id];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $commentId
     * @param  $type
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $commentId, $type)
    {

        if ($type == "vote") {

            $this->validate($request, [
                'vote' => 'required',
                'user_id' => 'required',
            ]);

            $comments = Comment::find($commentId);

            $data = [
                "comment_id" => $commentId,
                'vote' => $request->vote,
                'user_id' => $request->user_id,
            ];


            if ($request->vote == "up") {
                $comment = $comments->first();
                $vote = $comment->votes;
                $vote++;
                $comments->votes = $vote;
                $comments->save();
            }


            if ($request->vote == "down") {
                $comment = $comments->first();
                $vote = $comment->votes;
                $vote--;
                $comments->votes = $vote;
                $comments->save();
            }

            if (CommentVote::create($data)) return "true";

        }

        if ($type == "spam") {

            $this->validate($request, [
                'user_id' => 'required',
            ]);

            $comments = Comment::find($commentId);
            $comment = $comments->first();
            $spam = $comment->spam;
            $spam++;
            $comments->spam = $spam;
            $comments->save();
            $data = [
                "comment_id" => $commentId,
                'user_id' => $request->user_id,
            ];
            if (CommentSpam::create($data)) return "true";
        }
    }

    /**
     * Get Comments for contragentId

     * @return Comments
     */

    public function show($contragentId)
    {

        $comments = Comment::where('contragent_id', $contragentId)->get();
        $commentsData = [];

        foreach ($comments as $key) {

            $user = User::find($key->user_id);
            $name = $user->name;
            $replies = $this->replies($key->id);
            $photo = $user->first()->photo_url;
            // dd($photo->photo_url);
            $reply = 0;
            $vote = 0;
            $voteStatus = 0;
            $spam = 0;

            if (Auth::user()) {

                $voteByUser = CommentVote::where('comment_id', $key->id)->where('user_id', Auth::user()->id)->first();
                $spamComment = CommentSpam::where('comment_id', $key->id)->where('user_id', Auth::user()->id)->first();

                if ($voteByUser) {
                    $vote = 1;
                    $voteStatus = $voteByUser->vote;
                }

                if ($spamComment) $spam = 1;
            }

            if (!empty($replies) > 0) $reply = 1;

            if (!$spam) {
                array_push($commentsData, [
                    "name" => $name,
                    "photo_url" => (string) $photo,
                    "commentid" => $key->id,
                    "comment" => $key->comment,
                    "votes" => $key->votes,
                    "reply" => $reply,
                    "votedByUser" => $vote,
                    "vote" => $voteStatus,
                    "spam" => $spam,
                    "replies" => $replies,
                    "date" => $key->created_at->toDateTimeString()
                ]);
            }
        }

        $collection = collect($commentsData);
        return $collection->sortBy('votes');
    }

    protected function replies($commentId)
    {

        $comments = Comment::where('reply_id', $commentId)->get();
        $replies = [];

        foreach ($comments as $key) {

            $user = User::find($key->user_id);
            $name = $user->name;
            $photo = $user->first()->photo_url;
            $vote = 0;
            $voteStatus = 0;
            $spam = 0;

            if (Auth::user()) {

                $voteByUser = CommentVote::where('comment_id', $key->id)->where('user_id', Auth::user()->id)->first();
                $spamComment = CommentSpam::where('comment_id', $key->id)->where('user_id', Auth::user()->id)->first();

                if ($voteByUser) {
                    $vote = 1;
                    $voteStatus = $voteByUser->vote;
                }

                if ($spamComment) $spam = 1;
            }

            if (!$spam) {
                array_push($replies, [
                    "name" => $name,
                    "photo_url" => $photo,
                    "commentid" => $key->id,
                    "comment" => $key->comment,
                    "votes" => $key->votes,
                    "votedByUser" => $vote,
                    "vote" => $voteStatus,
                    "spam" => $spam,
                    "date" => $key->created_at->toDateTimeString()
                ]);
            }
            $collection = collect($replies);
            return $collection->sortBy('votes');
        }
    }
}
