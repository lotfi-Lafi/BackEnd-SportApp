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

	public function teamAccepted()
	{
    	return $this->belongsToMany('App\Team')
    	->wherePivot('status', 1);
	}

	public function teamRefused()
	{
    	return $this->belongsToMany('App\Team')
    	->wherePivot('status', 2);
	}

	public function teamCurrent()
	{
    	return $this->belongsToMany('App\Team')
    	->wherePivot('status', 0);
	}

	public function organizer()
    {
        return $this->belongsTo('App\Organizer');
    }


    public function teamNotInvited()
	{
    	return $this->belongsToMany('App\Team');
    	
	}

    public function match()
    {
        return $this->hasMany('App\Match');
    }
}
