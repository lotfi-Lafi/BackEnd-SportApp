<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LRedis;
class MatchController extends Controller
{
    public function addMatch(Request $request)
    {
    	$redis = LRedis::connection();
    	$redis->publish('message','bonjour');
    }
}
