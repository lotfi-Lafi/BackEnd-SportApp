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
        
  		
    	if ($request->nameChampion && $request->nbrTeamChampion && $request->typeChampion && $request->datestartChampion && 
    		$request->dateendChampion && $request->datestartChampion < $request->dateendChampion )
    	{
    		

    		$competition = new Competition();

    		$competition->name 				= $request->nameChampion;
    		$competition->typeTeams 		= $request->nbrTeamChampion;
    		$competition->typeCompetition   = $request->typeChampion;
    		$competition->start 			= $request->datestartChampion;
    		$competition->end 				= $request->dateendChampion;

    		$competition->save();

            $area = json_decode($request->tableau, true);

            $user1 = User::where('id', '=', 10)->first();
            $user2 = User::where('id', '=', 13)->first();

            foreach ($area as $item) 
            {
                
                $now = Carbon::now();
                $competition->team()->attach($item, ['status' => 0,'created_at' => $now->toDateTimeString(),'updated_at' => $now->toDateTimeString()]);
                
            }

            $push = new PushNotification;

            $push->setMessage([
                'notification' => [
                    'title'=>'This is the title',
                    'body'=>'This is the message',
                    'sound' => 'default'
                    ],
            'data' => [
                    'title' => 'ya saberrrrrr',
                    'message' => 'value2'
                    ]
            ])
                ->setApiKey('AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c')
                ->setDevicesToken([$user2->tokenDevice,$user1->tokenDevice]);


        $push = $push->send();
    		return response()->json(" successfully create champion");
    	}else
    	{
    		return response()->json("error date or value");
    	}
    	
    }
}
