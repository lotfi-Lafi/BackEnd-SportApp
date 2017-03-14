<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
	
    protected $fillable = [
        'etat'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
