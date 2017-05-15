<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\AddMatchEvent;
use LRedis;
use App\User;
use App\Match;
use App\Team;
use App\HalfTime;
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

                $match->competition_id  = $request->id;
                $match->teamOne         = $key;
                $match->teamTwo         = $value;
                $match->resultat        = "0-0";
                $match->winner          = 3;
                $match->code            = rand(10000,99999);

                $match->save();

                $halfTime1 = new HalfTime;
                $halfTime1->match_id   = $match->id;
                $halfTime1->resultat   = "0-0";
                $halfTime1->save();

                $halfTime2 = new HalfTime;
                $halfTime2->match_id   = $match->id;
                $halfTime2->resultat   = "0-0";
                $halfTime2->save();

                /*
                0 = null
                1 = team one winner
                2 = Team two winner
                3 = game to playe
                4 = live game
                */

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
              ->with('competition')->with('halfTime')->get()->first();

            $teamOne = Team::where('id', '=', $match->teamOne)->with('teamHasClient.client.user')->first();
            $teamTwo = Team::where('id', '=', $match->teamTwo)->with('teamHasClient.client.user')->first();

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

    public function getLiveMatchs()
    {
        $result=array();
        $liveMatchs = Match::where('winner', '=', 4)->get();
        foreach ($liveMatchs as $value) 
        {
            $teamOne = Team::where('id', '=', $value->teamOne)->first();
            $teamTwo = Team::where('id', '=', $value->teamTwo)->first();
          $result[] =  array(
                 'liveMatchs'       => $value,
                 'teamOne'          => $teamOne,
                 'teamTwo'          => $teamTwo,
                 
                 );
        }
        return response()->json($result); 
    }

    public function getLiveMatch(Request $request)
    {
        if($request->id)
        {
            $result=array();
            $liveMatch = Match::where('id', '=', $request->id)->where('winner', '=', 4)->first();
         
                $teamOne = Team::where('id', '=', $liveMatch->teamOne)->first();
                $teamTwo = Team::where('id', '=', $liveMatch->teamTwo)->first();
              $result[] =  array(
                     'liveMatchs'       => $liveMatch,
                     'teamOne'          => $teamOne,
                     'teamTwo'          => $teamTwo,
                     
                     );
            
            return response()->json($result);
        }else
        {
            return response()->json("error id live match !!");
        }
         
    }

    public function editResultatMatch(Request $request)
    {
        if ($request->oneOrTwo && $request->half_time_id && $request->time && $request->player && 
                $request->team && $request->idMatch )
        {
            $matchTest = Match::where("id",$request->idMatch)->first();
            if ($matchTest)
            {
                if ($request->oneOrTwo == 1)

                   $match = Match::where("id",$request->idMatch)->increment('resultatTeamOne');
                else
                   $match = Match::where("id",$request->idMatch)->increment('resultatTeamTwo');
      
                return response()->json(" successfully Update Match");
            }else
            {
                return response()->json("error id match !!");
            }
                
           
        }else
        {
            return response()->json("error !!");
        }
            
    }
}
