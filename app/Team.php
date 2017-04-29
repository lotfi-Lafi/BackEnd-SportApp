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

    public function client()
    {
        return $this->belongsToMany('App\Client')->withPivot('description',
            'defense','middlefield','offensive','created_at','updated_at');
        
    }

	public function categoryTeams()
	{
    	return $this->belongsToMany('App\CategoryTeams');
    	
	}

}
