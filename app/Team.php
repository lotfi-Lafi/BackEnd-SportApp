<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    public function teamHasClient()
    {
        return $this->hasMany('App\TeamHasClient');
       
    }

}