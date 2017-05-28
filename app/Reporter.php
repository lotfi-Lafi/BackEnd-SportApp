<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reporter extends Model
{
    public function match()
    {
        return $this->hasMany('App\Match');
    }
}
