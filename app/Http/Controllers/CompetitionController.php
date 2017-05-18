<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Client;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuthExceptions\JWTException;
use JWTAuth;
use App\User;
use App\Team;
use App\Match;
use App\Organizer;
use App\Competition;
use DB;

use Edujugon\PushNotification\PushNotification;
class CompetitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 
         	'signUpClient']]);
    }

    public function addCompetition(Request $request)
    {
  		$userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);

        if ($user->role == 'ORGANIZER')
        {
            $organizer = Organizer::where('user_id', '=', $user->id)->first();

            if ($request->nameChampion && $request->nbrTeamChampion && $request->typeChampion && $request->datestartChampion && 
                $request->dateendChampion && $request->datestartChampion < $request->dateendChampion )
            {
                

                $competition = new Competition();

                $competition->organizer_id      = $organizer->id;
                $competition->name              = $request->nameChampion;
                $competition->typeTeams         = $request->nbrTeamChampion;
                $competition->typeCompetition   = $request->typeChampion;
                $competition->status            = 'construction';
                $competition->start             = $request->datestartChampion;
                $competition->end               = $request->dateendChampion;

                $competition->save();

                $area = json_decode($request->tableau, true);

                $user1 = User::where('id', '=', '10')->first();
                $user2 = User::where('id', '=', '11')->first();

                foreach ($area as $item) 
                {
                    
                    $now = Carbon::now();
                    $competition->team()->attach($item, ['status' => 0,'created_at' => $now->toDateTimeString(),'updated_at' => $now->toDateTimeString()]);
                    
                    // send notification to members of team :
                    $t = Team::find($item);

                    foreach ($t->teamHasClient as $client) 
                    {
                        $cl = Client::find($client->client_id);


                        $push = new PushNotification;

                        $push->setMessage([
                            'notification' => [
                                'title'=>'This is the title',
                                'body'=>'This is the message',
                                'sound' => 'default'
                                ],
                        'data' => [
                                'title' => 'This is the title',
                                'message' => 'value2'
                                ]
                        ])
                            ->setApiKey('AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c')
                            ->setDevicesToken([$cl->user->tokenDevice]);
                        $push = $push->send();

                    }
                    
                }

                
                return response()->json(" successfully create champion");
            }else
            {
                return response()->json("error date or value");
            }

        }else
        {
            return response()->json("error permison");
        }
        
    	
    }

    
    public function addToCompetitionExistante(Request $request)
    {

        if ($request->idCompetition)
        {
            $competition =  Competition::find($request->idCompetition);
                
            $area = json_decode($request->tableau, true);

            foreach ($area as $item) 
            {
                $now = Carbon::now();
                    $competition->team()->attach($item, ['status' => 0,'created_at' => $now->toDateTimeString(),'updated_at' => $now->toDateTimeString()]);
                    
                    // send notification to members of team :
                    $t = Team::find($item);

                    foreach ($t->teamHasClient as $client) 
                    {
                        $cl = Client::find($client->client_id);


                        $push = new PushNotification;

                        $push->setMessage([
                            'notification' => [
                                'title'=>'This is the title',
                                'body'=>'This is the message',
                                'sound' => 'default'
                                ],
                        'data' => [
                                'title' => 'notification to competition',
                                'message' => 'value2'
                                ]
                        ])
                            ->setApiKey('AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c')
                            ->setDevicesToken([$cl->user->tokenDevice]);
                        $push = $push->send();

                    }
            }

                
         return response()->json(" successfully update champion");
                
           
        }else
        {
            return response()->json("error id Competition");
        }
        
        
    }

    public function validCompetition(Request $request)
    {
        if ($request->idCompetition)
        {
            $competition =  Competition::where('id','=',$request->idCompetition)
            ->update(array('status' => 'valid'));

            return response()->json("successfully valid champion");
        }else
        {
            return response()->json("error id Competition");
        }
    }

    public function cancelCompetition(Request $request)
    {
        if ($request->idCompetition)
        {
            $competition =  Competition::where('id','=',$request->idCompetition)
            ->update(array('status' => 'cancel'));

            return response()->json("successfully cancel champion");
        }else
        {
            return response()->json("error id Competition");
        }
    }

    public function getCompetitionConstruction()
    {   
        $competitionConstruction        = Competition::where('status','=','construction')->get();
        $numbersCompetitionConstruction = Competition::where('status','=','construction')->count();

        return response()->json(['competitionConstruction' =>$competitionConstruction ,'numbersCompetitionConstruction' => $numbersCompetitionConstruction]);
    }

    public function getCompetitionConstructionAccepted(Request $request)
    {   
        if ($request->id)
        {
            $teamAccepted = Competition::where('id','=',$request->id)
                                ->where('status','=','construction')
                                ->with('teamAccepted')
                                ->get();
    
            return response()->json($teamAccepted);
        }else
        {
            return response()->json("error id competition !");
        }
    }


    public function getCompetitionConstructionRefused(Request $request)
    {  
        if ($request->id)
        {
            $teamRefused = Competition::where('id','=',$request->id)
                                ->where('status','=','construction')
                                ->with('teamRefused')
                                ->get();
    
            return response()->json($teamRefused);
        }else
        {
            return response()->json("error id competition !");
        }

    }

    public function getCompetitionConstructionCurrent(Request $request)
    {  
        if ($request->id)
        {
            $teamCurrent = Competition::where('id','=',$request->id)
                                ->where('status','=','construction')
                                ->with('teamCurrent')
                                ->get();
    
            return response()->json($teamCurrent);
        }else
        {
            return response()->json("error id competition !");
        }
    }


    public function getTeamsNotInvitedToCompetition(Request $request)
    {  
        if ($request->id)
        {

            $t = DB::table('competition_team')
                  ->where('competition_team.competition_id', '=', $request->id)
                  ->pluck('team_id')->toArray();

            $result = DB::table('teams')->whereNotIn('id', $t)->get();

            return response()->json($result);

        }else
        {
            return response()->json("error id competition !");
        }
    }

    public function teamsOfCompetitionValide(Request $request)
    {   
        if ($request->id)
        {

            $c = Competition::where('id', '=', $request->id)->with('team')->get();
           /* $result = DB::table('competition_team')
                  ->where('competition_team.competition_id', '=', $request->id)
                  ->pluck('team_id')->toArray();

            $result = DB::table('teams')->whereNotIn('id', $t)->get();*/

            return response()->json($c);

        }else
        {
            return response()->json("error id competition !");
        }
    }

    public function myCompetitionByTeam(Request $request)
    {   
        if ($request->id)
        {

            $team = Team::where('id', '=', $request->id)
            ->with('myCompetitionAccepted')->get();

            return response()->json($team);
        }else
        {
            return response()->json("error id team !");
        }
    }


    public function matchsByCompetition(Request $request)
    {   
        if ($request->id)
        {
            $result=array();
            $matchs = Competition::where('id', '=', $request->id)->with('match.halfTime')->get();

            foreach ($matchs as $value) 
            {
                foreach ($value->match as $m) 
                {
                    $teamOne = Team::where('id', '=', $m->teamOne)->first();
                    $teamTwo = Team::where('id', '=', $m->teamTwo)->first();
                    $result[] =  array(

                         'match'        => $m,
                         'teamOne'      => $teamOne,
                         'teamTwo'      => $teamTwo,
                         
                         );
                }
            }

            return response()->json($result);
        }else
        {
            return response()->json("error id competition !");
        }
    }


    public function goalsByMatch(Request $request)
    {   
        if ($request->id)
        {
            $result=array();
            $goalsTeamOne=array();
            $goalsTeamTwo=array();

            
            

            $match = Match::where('id', '=', $request->id)
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
            
            $teamOne = Team::where('id', '=', $match->teamOne)->first();
            $teamTwo = Team::where('id', '=', $match->teamTwo)->first();

            $result[] =  array(
                     'match'            => $match,
                     'teamOne'          => $teamOne,
                     'teamTwo'          => $teamTwo,
                     'goalsTeamOne'     => $goalsTeamOne,
                     'goalsTeamTwo'     => $goalsTeamTwo,
                     );

            return response()->json($result);
        }else
        {
            return response()->json("error id match !");
        }
    }


    public function allCompetitonValid()
    {  
        $competitions = Competition::where('status', '=','valid')->get();
        return response()->json($competitions);
    }

}
