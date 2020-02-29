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
        'by',
        'to'
    ];
    protected $fillable = [
        'comment',
        'votes',
        'contragent_id',
        'writer',
        'picture'
    ];
    protected $dates = ['created_at', 'updated_at'];


    public function getToAttribute()
    {
        $to = Contragent::find($this->contragent_id);
        return $to->title;
    }

    public function getByAttribute()
    {
        $by = Contragent::find($this->writer);
        return $by->title;
    }
}
