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
        foreach ($request->tableau as $item) 
            {
                return response()->json($item);
            } 
        return response()->json('errorrrrrrrrr');
  		
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

            foreach ($request->tableau as $item) 
            {
               var_dump($item->product->brand);
            }   
    		return response()->json(" successfully create champion");
    	}else
    	{
    		return response()->json("error date or value");
    	}
    	
    }
}
