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
use App\TeamHasClient;
class TeamController extends Controller
{
    public function __construct()
    {
         $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 
         	'signUpClient']]);
    }

    public function getAllTeams()
    {

    	$teams = Team::with('teamHasClient.client.user')->get();
    	return response()->json($teams);
    	
    	/*$teams = Team::all();

		   foreach($teams as $team)
		   {

		       foreach($team->teamHasClient  as $teamHASClient)
		       {
		       	
		       	$result[] =  array([
		   		 'name' 			=> $team->name,
		   		 'logo' 			=> $team->logo,
		   		 'city'				=> $team->city,
		   		 'teamHasClient_id' => $teamHASClient->id,
		   		 'client_id' 		=> $teamHASClient->client_id,
		   		 'user_id' 			=> $teamHASClient->client->user_id,
		   		 'nameUser' 		=> $teamHASClient->client->user->name
		   		 ]);

		   	
		       	
				   
		       }
		   }
		   return response()->json($result);*/
    }

    public function createTeam(Request $request)
    {
    	
    	 $rules = array(
            'name'      => 'required',                        
            'logo' 		=> 'required',
            'city' 		=> 'required'
        );

    	$userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);
        $client = Client::where('user_id', '=', $user->id)->first();

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();
            return response()->json($messages);
        }else 
        {
            
            $team = new Team;
       
            $team->name         = $request->get('name');
         	$team->logo 		= $request->get('logo');
            $team->city 		= $request->get('city');
            
            $team->save();

            $teamHasClient = new TeamHasClient;

            $now = Carbon::now();
    	
            $teamHasClient->client_id    		= $client->id;
            $teamHasClient->team_id    	 		= $team->id;
            $teamHasClient->dateJoinOrRreate    = $now->toDateTimeString();
            $teamHasClient->type    			= 'CREATE';

            $teamHasClient->save();
           
            return response()->json("Team has created !");
        }
    }
}
