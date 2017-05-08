<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    public function halfTime()
    {
        return $this->belongsTo('App\HalfTime');
    }
}
