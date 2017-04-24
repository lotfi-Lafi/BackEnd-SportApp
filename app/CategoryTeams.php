<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryTeams extends Model
{
    public function team()
	{
    	return $this->belongsToMany('App\Team');
    	
	}
}
