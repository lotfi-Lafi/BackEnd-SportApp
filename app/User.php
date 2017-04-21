<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','phone', 'adresse','country','city','birthday','photo',
        'role'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function organizer()
    {
        return $this->hasOne('App\Organizer');
    }

    public function client()
    {
        return $this->hasOne('App\Client');
    }



    // friends
    public function friends()
    {
        $friends = $this->belongsToMany('App\User', 'user_friend_user', 'user_id_one', 'user_id_two')->withPivot('status','created_at','updated_at');
        return $friends;
    }

    public function friends2()
    {
        $friends = $this->belongsToMany('App\User', 'user_friend_user', 'user_id_two', 'user_id_one')->withPivot('status','created_at','updated_at');
        return $friends;
    }

     public function friends3()
    {
        $friends = $this->belongsToMany('App\User', 'user_friend_user', 'user_id_two', 'user_id_one','user_id_one','user_id_two')->withPivot('status','created_at','updated_at');
        return $friends;
    }

    public function addfriend($friend_id_one,$friend_id)
    {
        $now = Carbon::now();
        
        $user1 = User::find($friend_id_one);
        $user1->friends()->attach($friend_id, ['status' => 0,'created_at' => $now->toDateTimeString(),'updated_at' => $now->toDateTimeString()]);
    }

    public function remove_friend($friend_id)
    {
        $this->friends()->detach($friend_id);   // remove friend
        $friend = Client::find($friend_id);       // find your friend, and...
        $friend->friends()->detach($this->id);  // remove yourself, too
    }
}
