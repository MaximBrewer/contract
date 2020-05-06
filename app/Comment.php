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
    
    protected $fillable = [
        'comment',
        'votes',
        'contragent_id',
        'writer'
    ];

    public function by(){
        return $this->belongsTo('App\Contragent', 'writer');
    }

    public function to(){
        return $this->belongsTo('App\Contragent', 'contragent_id');
    }

    public function getImagesAttribute(){
        return Attachment::where('entity', 'comment')->where('entity_id', $this->id)->get();
    }
}
