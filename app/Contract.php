<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Contract extends Model
{
    protected $fillable = [
        'acceptor_header',
        'contract_template_id',
        'contragent_id',
        'status'
    ];

    protected static $statuses = [
        0 => "расторгнут (не заключен)",
        1 => "запрос разрешения на подписание",
        2 => "подписан предложившим",
        3 => "договор подписан",
    ];


    public static function getStatus($status)
    {
        return static::$statuses[$status];
    }

    public function contractTemplate()
    {
        return $this->belongsTo('App\ContractTemplate');
    }

    public function getDateAttribute()
    {
        return date("d.m.Y г.", strtotime($this->created_at));
    }

}
 