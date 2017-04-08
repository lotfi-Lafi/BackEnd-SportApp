<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function teamHasClient()
    {
        return $this->hasMany('App\TeamHasClient');
    }

    public function teams()
    {
        return $this->hasManyThrough('App\Team', 'App\TeamHasClient');
    }

    public function skill()
    {
        return $this->hasMany('App\Skill');
    }

    public function position()
    {
        return $this->hasMany('App\Position');
    }

    
}
