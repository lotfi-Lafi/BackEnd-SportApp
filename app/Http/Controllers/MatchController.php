<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\AddMatchEvent;
use LRedis;
use App\User;
class MatchController extends Controller
{
    public function addMatch(Request $request)
    {
    	$users = User::all();
    /*	event(
    			new AddMatchEvent($users)
    		);*/
    	$redis = LRedis::connection();
    	$redis->publish('message',$users);

    	return response($users);
    	//return redirect()->back();
    }
}
