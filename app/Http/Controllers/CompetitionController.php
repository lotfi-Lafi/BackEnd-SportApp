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

}
