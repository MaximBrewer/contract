<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Comment;
use App\CommentSpam;
use App\CommentVote;
use Carbon\Carbon;
use App\User;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Support\Str;
use App\Attachment;
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
        $attributes = $request->all();

        $this->validate($request, [
            'comment' => 'required',
            'contragent_id' => 'required'
        ]);

        $comment = Comment::where('contragent_id', $attributes['contragent_id'])->where('writer', User::findOrFail(Auth::user()->id)->contragents[0]->id)->first();

        if ($comment) {
            $attributes['votes'] = $attributes['rate'];
            $comment->update($attributes);
        } else
            $comment = Comment::create([
                'contragent_id' => $attributes['contragent_id'],
                'comment' => $attributes['comment'],
                'votes' => $attributes['rate'],
                'writer' => User::findOrFail(Auth::user()->id)->contragents[0]->id,
            ]);
        if ($comment) {

            $path = 'comments' . DIRECTORY_SEPARATOR . $comment->id . DIRECTORY_SEPARATOR;
            $fullpath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;

            @mkdir($fullpath, 0777, true);
            foreach (Attachment::where('entity_id', $comment->id)->where('entity', 'comment')->get() as $attachment) {
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
                        'entity' => 'comment',
                        'entity_id' => $comment->id,
                        'sort' => (++$c) * 100,
                    ]);
                }
            }

            DB::select('update contragents set rating = (select (sum(votes)/count(id)) as total from comments where contragent_id = ?) where id = ?', [$attributes['contragent_id'], $attributes['contragent_id']]);
            $comments = CommentResource::collection(Comment::where('contragent_id', $attributes['contragent_id'])->orderBy('updated_at', 'desc')->get());
            return [
                $comments->collection,
                Contragent::find($attributes['contragent_id'])->rating,
                Comment::where('contragent_id', $attributes['contragent_id'])->where('writer', User::find(Auth::user()->id)->contragents[0]->id)->first()
            ];
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMy(Request $request)
    {
        $attributes = $request->all();

        $this->validate($request, [
            'comment' => 'required',
            'contragent_id' => 'required'
        ]);

        if ($request->hasfile('picture')) {
            $file = $request->file('picture');
            $extension = $file->getClientOriginalExtension();
            $path = 'auctions' . DIRECTORY_SEPARATOR . date('FY') . DIRECTORY_SEPARATOR;
            $fullpath = storage_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $path;
            @mkdir($fullpath, 0777, true);
            do {
                $filename = Str::random(20);
            } while (is_file($fullpath . $filename . '.' . $file->getClientOriginalExtension()));
            $file->move($fullpath, $filename . '.' . $extension);
            $attributes['picture'] = $path . $filename . '.' . $extension;
        }

        $comment = Comment::where('contragent_id', $attributes['contragent_id'])->where('writer', User::findOrFail(Auth::user()->id)->contragents[0]->id)->first();

        if ($comment) {
            $comment->update($attributes);
        } else
            $comment = Comment::create([
                'contragent_id' => $attributes['contragent_id'],
                'comment' => $attributes['comment'],
                'votes' => $attributes['votes'],
                'picture' => $attributes['picture'] ? $attributes['picture'] : '',
                'writer' => User::findOrFail(Auth::user()->id)->contragents[0]->id,
            ]);
        if ($comment) {
            DB::select('update contragents set rating = (select (sum(votes)/count(id)) as total from comments where contragent_id = ?) where id = ?', [$attributes['contragent_id'], $attributes['contragent_id']]);
            $comments = Comment::where('writer', User::find(Auth::user()->id)->contragents[0]->id)->orderBy('updated_at', 'desc')->get();
            return [
                $comments
            ];
        }
    }

    /**
     * Get Comments for contragentId

     * @return Comments
     */

    public function show($contragentId)
    {

        $comments = CommentResource::collection(Comment::where('contragent_id', $contragentId)->orderBy('updated_at', 'desc')->get());
        return [
            $comments->collection,
            Contragent::find($contragentId)->rating,
            new CommentResource(Comment::where('contragent_id', $contragentId)->where('writer', User::find(Auth::user()->id)->contragents[0]->id)->first())
        ];
    }

    /**
     * Get Comments for contragentId

     * @return Comments
     */

    public function index()
    {

        $comments = Comment::where('writer', User::find(Auth::user()->id)->contragents[0]->id)->orderBy('updated_at', 'desc')->get();
        return [
            $comments
        ];
    }
}
