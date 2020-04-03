<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class Settlement extends Model
{
    protected $fillable = [
        'contragent_id',
        'balance',
        'type',
        'status',
        'method'
    ];

    protected $appends = [
        'date',
        'sum',
        'textsum',
        'stextsum',
        //'month',
        'creq',
        'datetime',
        'bet',
    ];

    public function getSumAttribute()
    {
        return number_format($this->balance, 2, '.', ' ') . ' ₽';
    }


    public function getDatetimeAttribute()
    {
        return Date::parse($this->created_at)->format('H:i j F Y г.');
    }


    public function getDateAttribute()
    {
        return Date::parse($this->created_at)->format('j F Y г.');
    }

    public function getCreqAttribute()
    {


        // СПК "АГРОФИРМА КРАСНАЯ ЗВЕЗДА", ИНН 3507011492, КПП
        // 350701001,160515, ОБЛАСТЬ ВОЛОГОДСКАЯ, РАЙОН ВОЛОГОДСКИЙ,
        // ПОСЕЛОК СЕМЕНКОВО


        $str = $this->contragent->title . ", ИНН " . $this->contragent->inn;
        if ($this->contragent->kpp) $str .= ", КПП " . $this->contragent->kpp;
        if ($this->contragent->legal_address) $str .= ", " . $this->contragent->legal_address;
        return $str;
    }

    public function contragent()
    {
        return $this->belongsTo('App\Contragent');
    }

    public function bet()
    {
        return $this->belongsTo('App\Bet');
    }

    public function getTextsumAttribute()
    {
        $num = $this->balance;
        $nul = 'ноль';
        $ten = array(
            array('', 'один', 'два', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
            array('', 'одна', 'две', 'три', 'четыре', 'пять', 'шесть', 'семь', 'восемь', 'девять'),
        );
        $a20 = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать', 'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать', 'восемнадцать', 'девятнадцать');
        $tens = array(2 => 'двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят', 'семьдесят', 'восемьдесят', 'девяносто');
        $hundred = array('', 'сто', 'двести', 'триста', 'четыреста', 'пятьсот', 'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
        $unit = array( // Units
            array('копейка', 'копейки', 'копеек',     1),
            array('рубль', 'рубля', 'рублей', 0),
            array('тысяча', 'тысячи', 'тысяч', 1),
            array('миллион', 'миллиона', 'миллионов', 0),
            array('миллиард', 'милиарда', 'миллиардов', 0),
        );
        //
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub) > 0) {
            foreach (str_split($rub, 3) as $uk => $v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($unit) - $uk - 1; // unit key
                $gender = $unit[$uk][3];
                list($i1, $i2, $i3) = array_map('intval', str_split($v, 1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2 > 1) $out[] = $tens[$i2] . ' ' . $ten[$gender][$i3]; # 20-99
                else $out[] = $i2 > 0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk > 1) $out[] = self::morph($v, $unit[$uk][0], $unit[$uk][1], $unit[$uk][2]);
            } //foreach
        } else $out[] = $nul;
        $out[] = self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]); // rub
        $out[] = $kop . ' ' . self::morph($kop, $unit[0][0], $unit[0][1], $unit[0][2]); // kop
        return self::mb_ucfirst(trim(preg_replace('/ {2,}/', ' ', join(' ', $out))));
    }

    public function getStextsumAttribute()
    {
        $num = $this->balance;
        list($rub, $kop) = explode('.', sprintf("%015.2f", floatval($num)));
        $unit = array( // Units
            array('копейка', 'копейки', 'копеек',     1),
            array('рубль', 'рубля', 'рублей', 0),
        );
        return self::mb_ucfirst(trim(intval($rub) . ' ' . self::morph(intval($rub), $unit[1][0], $unit[1][1], $unit[1][2]) . ' ' . $kop . ' ' . self::morph(intval($kop), $unit[0][0], $unit[0][1], $unit[0][2])));
    }

    private static function mb_ucfirst($string, $enc = 'UTF-8')
    {
        return mb_strtoupper(mb_substr($string, 0, 1, $enc), $enc) .
            mb_substr($string, 1, mb_strlen($string, $enc), $enc);
    }

    private static function morph($n, $f1, $f2, $f5)
    {
        $n = abs(intval($n)) % 100;
        if ($n > 10 && $n < 20) return $f5;
        $n = $n % 10;
        if ($n > 1 && $n < 5) return $f2;
        if ($n == 1) return $f1;
        return $f5;
    }
}
