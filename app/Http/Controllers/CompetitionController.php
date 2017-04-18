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

            foreach ($area as $item) 
            {
                
                $now = Carbon::now();
                $competition->team()->attach($item, ['status' => 0,'created_at' => $now->toDateTimeString(),'updated_at' => $now->toDateTimeString()]);
                
            }
    		return response()->json(" successfully create champion");
    	}else
    	{
    		return response()->json("error date or value");
    	}
    	
    }
}
