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
            $goalsTeamOne=array();
            $goalsTeamTwo=array();

            
            

            $match = Match::where('id', '=', $request->id)->where('winner', '=', 4)->with('halfTime.goal')
            ->first();

            $t1 = $match->teamOne;
            $t2 = $match->teamTwo;

           //dd($match->halfTime[0]->goal2($t1)->get());
            foreach ($match->halfTime as $HT) 
            {
                foreach ($HT->goal2($t1)->get() as $value) 
                {
                    $user = User::where('id', '=', $value->player)->first();

                    $goalsTeamOne[] =  array(
                         'goal'       => $value,
                         'user'       => $user,
                         
                         );
                }    
            }

            foreach ($match->halfTime as $HT) 
            {
                foreach ($HT->goal2($t2)->get() as $value) 
                {
                    $user = User::where('id', '=', $value->player)->first();

                    $goalsTeamTwo[] =  array(
                         'goal'       => $value,
                         'user'          => $user,
                         
                         );
                }    
            }
            




            

            $resultTeamOneHalfTimeOne = $match->halfTime[0]->goal2($t1)->count();
            $resultTeamOneHalfTimeTwo = $match->halfTime[1]->goal2($t1)->count();
            $resultTeamOneTotal = $resultTeamOneHalfTimeOne + $resultTeamOneHalfTimeTwo;

            $resultTeamTwoHalfTimeOne = $match->halfTime[0]->goal2($t2)->count();
            $resultTeamTwoHalfTimeTwo = $match->halfTime[1]->goal2($t2)->count();
            $resultTeamTwoTotal = $resultTeamTwoHalfTimeOne + $resultTeamTwoHalfTimeTwo;

            Match::where('id', '=', $request->id)->where('winner', '=', 4)
             ->update(['resultatTeamOne' => $resultTeamOneTotal ,'resultatTeamTwo' => $resultTeamTwoTotal]);

             $liveMatch = Match::where('id', '=', $request->id)->where('winner', '=', 4)
             ->first();

        
         
               $teamOne = Team::where('id', '=', $liveMatch->teamOne)->first();
                $teamTwo = Team::where('id', '=', $liveMatch->teamTwo)->first();
              $result[] =  array(
                     'liveMatchs'       => $liveMatch,
                     'teamOne'          => $teamOne,
                     'teamTwo'          => $teamTwo,
                     'goalsTeamOne'     => $goalsTeamOne,
                     'goalsTeamTwo'     => $goalsTeamTwo,
                     );
            
            return response()->json($result);
        }else
        {
            return response()->json("error id live match !!");
        }
         
    }

    public function editResultatMatch(Request $request)
    {
        if ($request->idMatch)
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


   /* public function getLiveMatch(Request $request)
    {
        if($request->id)
        {
            $result=array();
            
            

            $match = Match::where('id', '=', $request->id)->where('winner', '=', 4)->with('halfTime.goal')
            ->first();



            $t1 = $match->teamOne;
            $t2 = $match->teamTwo;

            $resultTeamOneHalfTimeOne = $match->halfTime[0]->goal2($t1)->count();
            $resultTeamOneHalfTimeTwo = $match->halfTime[1]->goal2($t1)->count();
            $resultTeamOneTotal = $resultTeamOneHalfTimeOne + $resultTeamOneHalfTimeTwo;

            $resultTeamTwoHalfTimeOne = $match->halfTime[0]->goal2($t2)->count();
            $resultTeamTwoHalfTimeTwo = $match->halfTime[1]->goal2($t2)->count();
            $resultTeamTwoTotal = $resultTeamTwoHalfTimeOne + $resultTeamTwoHalfTimeTwo;

            Match::where('id', '=', $request->id)->where('winner', '=', 4)
             ->update(['resultatTeamOne' => $resultTeamOneTotal ,'resultatTeamTwo' => $resultTeamTwoTotal]);

             $liveMatch = Match::where('id', '=', $request->id)->where('winner', '=', 4)
             ->first();

        
         
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
         
    }*/
}
