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
use App\HalfTime;
use App\Reporter;
use DB;

use Edujugon\PushNotification\PushNotification;

class AccueilController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 
         	'signUpClient']]);
    }

    public function getResultatLiveMatchs()
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

}
