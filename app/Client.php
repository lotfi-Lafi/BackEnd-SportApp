<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function teamHasClient()
    {
        return $this->hasMany('App\TeamHasClient');
    }

    public function teams()
    {
        return $this->hasManyThrough('App\Team', 'App\TeamHasClient');
    }
}
