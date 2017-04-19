<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    public function teamHasClient()
    {
        return $this->hasMany('App\TeamHasClient');
       
    }

    public function competition()
	{
    	return $this->belongsToMany('App\Competition')->withPivot('status','created_at','updated_at');
        
	}

}
