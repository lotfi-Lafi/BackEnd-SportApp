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

  		dd($request);
    	if ($request->name && $request->typeTeams && $request->typeCompetition && $request->start && 
    		$request->end && $request->start < $request->end )
    	{
    		

    		$competition = new Competition();

    		$competition->name 				= $request->name;
    		$competition->typeTeams 		= $request->typeTeams;
    		$competition->typeCompetition   = $request->typeCompetition;
    		$competition->start 			= $request->start;
    		$competition->end 				= $request->end;

    		$competition->save();

    		return response()->json(" successfully");
    	}else
    	{
    		return response()->json("error date or value");
    	}
    	
    }
}
