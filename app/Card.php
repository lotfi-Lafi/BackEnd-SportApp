<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public function halfTime()
    {
        return $this->belongsTo('App\HalfTime');
    }
}
