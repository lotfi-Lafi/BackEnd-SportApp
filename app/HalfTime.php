<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HalfTime extends Model
{
    public function match()
    {
        return $this->belongsTo('App\Match');
    }

    public function card()
    {
        return $this->hasMany('App\Card');
    }

    public function goal()
    {
        return $this->hasMany('App\Goal');
    }

    public function goal2($id)
    {
        return $this->hasMany('App\Goal')->where('team','=',$id);
    }
}
