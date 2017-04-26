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

    public function getTeam(Request $request)
    {
        if ($request->id)
        {
            $team = Team::find($request->id);

            if ($team)
            return response()->json($team); 
            else
            return response()->json(['error','teams does not exist !']);  

        }else
        {
           return response()->json(['error','teams does not exist !']);  
        }
        
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

    public function getAllMyTeams()
    {
        $userAuth = JWTAuth::parseToken()->authenticate();
        $client = Client::where('user_id', '=', $userAuth->id)
        ->with('teamHasClient.team')->get();

        return response()->json($client);
    }


    public function permissionCreateTeam()
    {
        $permission = true;
        $userAuth = JWTAuth::parseToken()->authenticate();
        $client = Client::where('user_id', '=', $userAuth->id)->first();

        $result = TeamHasClient::where('client_id', '=', $client->id)
                  ->where('type', '=', "CREATE")
                  ->count();

        if($result == 0)
            $permission = true;
        else
            $permission = false;
        
        return response()->json($permission);
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

            // add client "CREATE" 
            $userAuth = JWTAuth::parseToken()->authenticate();
            $clientCreate = Client::where('user_id', '=', $userAuth->id)->first();

                $teamHasClient = new TeamHasClient;

                $now = Carbon::now();

                $teamHasClient->client_id           = $clientCreate->id;
                $teamHasClient->team_id             = $team->id;
                $teamHasClient->dateJoinOrRreate    = $now->toDateTimeString();
                $teamHasClient->dateLeft            = null;
                $teamHasClient->type                = 'CREATE';

                $teamHasClient->save(); 

            // add clients "JOIN"
            $area = json_decode($request->tableau, true);
            foreach ($area as $item) 
            {
                $teamHasClient = new TeamHasClient;

                $now = Carbon::now();

                $clet = Client::where('user_id', '=', $item)->first();
            
                $teamHasClient->client_id           = $clet->id;
                $teamHasClient->team_id             = $team->id;
                $teamHasClient->dateJoinOrRreate    = $now->toDateTimeString();
                $teamHasClient->dateLeft            = null;
                $teamHasClient->type                = 'JOIN';

                $teamHasClient->save();     
            }

           


            //$team->categoryTeams()->sync($request->categoryTeams, false);
           
            return response()->json("Team has created !");
        }
    }

    public function deleteTeam(Request $request)
    {
        if ($request->id)
        {

            $team = Team::find($request->id);
            if ($team)
            {
                $team->delete();
                return response()->json("deleted successfully !");
            }
            else
            return response()->json(['error','teams does not exist, deleted error !']);

        }else
        {
           return response()->json(['error','deleted error !']);  
        }
        
    }
}
