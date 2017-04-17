<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    public function team()
	{
    	return $this->belongsToMany('App\Team')
        ->withPivot('status','created_at','updated_at');
	}
}
