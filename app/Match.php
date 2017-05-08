<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

    public function halfTime()
    {
        return $this->hasMany('App\HalfTime');
    }
}
