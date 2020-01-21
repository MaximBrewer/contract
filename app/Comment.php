<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    //
    /**
     * Fillable fields for a course
     *
     * @return array
     */


    protected $appends = [
        'writer'
    ];
    protected $fillable = ['comment', 'votes', 'contragent_id', 'writer'];
    protected $dates = ['created_at', 'updated_at'];


    public function getWriterAttribute()
    {
        $contragent = Contragent::find($this->contragent_id)->select('title')->first();
        return $contragent->title;
    }


    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }
}
