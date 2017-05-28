<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;
class Match extends Model
{
	 protected $fillable = [
        'teamOne', 'teamTwo'

    ];
    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

    public function reporter()
    {
        return $this->belongsTo('App\Reporter');
    }

    public function halfTime()
    {
        return $this->hasMany('App\HalfTime');
    }

    public function liveMatchs()
    {
        $team = Team::find($this->teamOne);
                return $team;
    }

}
