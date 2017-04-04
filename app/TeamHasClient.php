<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamHasClient extends Model
{
    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function team()
    {
        return $this->belongsTo('App\Team');
    }
}
