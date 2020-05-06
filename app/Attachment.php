<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $fillable = [
        'title',
        'url',
        'entity',
        'entity_id',
        'sort',
        'writer'
    ];

    protected $appends = ['path'];
    
    public function getPathAttribute(){
        return '/storage/' . $this->url;
    }
}
