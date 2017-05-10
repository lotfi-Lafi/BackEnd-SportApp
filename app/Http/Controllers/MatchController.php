<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\AddMatchEvent;
use LRedis;
use App\User;
use App\Match;
use App\Team;
class MatchController extends Controller
{
/*    public function addMatch(Request $request)
    {
    	$users = User::find(7);

    	$redis = LRedis::connection();
    	$redis->publish('message',$users);

    	return response($users);
    }*/

    public function addMatch(Request $request)
    {
        if ($request->tableau)
        {
            
            $area = json_decode($request->tableau, true);
            //$area = array_flip($request->tableau);
            foreach ($area as  $key => $value) 
            {
               //return response()->json($value);
                
                $match = new Match;

                $match->competition_id  = $request->id  ;
                $match->teamOne         = $key;
                $match->teamTwo         = $value;
                $match->code            = rand(10000,99999);

                $match->save();
          
            }

                
         return response()->json(" successfully create Match");
                
           
        }else
        {
            return response()->json("error id Competition");
        }
    }


    public function getMatch(Request $request)
    {
        if ($request->codeMatch)
        {
            $match = Match::where('code', '=', $request->codeMatch)
              ->with('competition')->get()->first();

            $teamOne = Team::where('id', '=', $match->teamOne)->with('client.user')->first();
            $teamTwo = Team::where('id', '=', $match->teamTwo)->with('client.user')->first();

            $result[] =  array([
                 'match'            => $match,
                 'teamOne'          => $teamOne,
                 'teamTwo'          => $teamTwo,
                 
                 ]);

             return response()->json($result[0]); 

        }else
        {
           return response()->json(['error','error code match !']);  
        }
    }
}
