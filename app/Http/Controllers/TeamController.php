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
use App\Goal;
use DB;
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
            $team = Team::where('id', '=', $request->id)
              ->with('teamHasClient.client.user')->get();

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

     public function searchTeams(Request $request)
    {

        $userAuth = JWTAuth::parseToken()->authenticate();
        $clients = Client::where('user_id', '=', $userAuth->id)
        ->with('teamHasClient.team')->get();


        $teamArray=[];
       
        foreach ($clients as $client) 
        {   
             foreach ($client->teamHasClient as  $key => $value) 
            {
               
                $teamArray[$key]=$value->team->id;
            }

        }

        $name = $request->name;
        $customer = DB::table('teams')
                ->whereNotIn('id', $teamArray)
                ->where('teams.name', 'LIKE', "%$name%")
                ->get();
        return response()->json($customer);

    }


    public function searchTeamsAll()
    {

        $userAuth = JWTAuth::parseToken()->authenticate();
        $clients = Client::where('user_id', '=', $userAuth->id)
        ->with('teamHasClient.team')->get();


        $teamArray=[];
      
        foreach ($clients as $client) 
        {   
             foreach ($client->teamHasClient as  $key => $value) 
            {
               
                $teamArray[$key]=$value->team->id;
            }

        }

        $customer = DB::table('teams')
                ->whereNotIn('id', $teamArray)
                ->get();
        return response()->json($customer);

    }

    public function addEvaluationToTeam (Request $request)
    {
        $userAuth = JWTAuth::parseToken()->authenticate();
        $client = Client::where('user_id', '=', $userAuth->id)
        ->get()->first();


        $team = Team::where('id', '=', $request->id)->get()->first();
     
        if ($team)
        {
            $now = Carbon::now();

            $client->team()->attach($team->id, ['description' => $request->description,'defense' => $request->defense,
                'middlefield' => $request->middlefield,
                'offensive' => $request->offensive,
                'created_at' => $now->toDateTimeString(),'updated_at' => $now->toDateTimeString()]);

          
            return response()->json('Successfully add evaluation to team !');
        }
        else
        return response()->json("error !!");
    }


     public function getEvaluationTeam(Request $request)
    {
        $team = Team::with('client.user')->where('id', '=', $request->id)->get()->first();

       // return response()->json($team->client->count());
       /* $user = User::where('id', '=', $request->id)
        ->with('client.skill','client.position')
        ->get()
        ->first();*/

        $avgDefense=0;
        $avgMiddlefield=0;
        $avgOffensive=0;

        $countTotal =$team->client->count();

        if ($countTotal > 0)
        {
            foreach ($team->client as $cl) 
            {
                $avgDefense+=$cl->pivot->defense;
                $avgMiddlefield+=$cl->pivot->middlefield;
                $avgOffensive+=$cl->pivot->offensive;
            }

            return response()->json([
                'teams'              => $team, 
                'avgDefense'         => number_format($avgDefense/$countTotal,2).'/10  ('.$countTotal.' Clients)',
                'avgMiddlefield'     => number_format($avgMiddlefield/$countTotal,2).'/10  ('.$countTotal.' Clients)',
                'avgOffensive'       => number_format($avgOffensive/$countTotal,2).'/10  ('.$countTotal.' Clients)',
               
                ]);
        }else
        {
            return response()->json([
                'teams'              => null, 
                'avgDefense'         => '(0 Clients)',
                'avgMiddlefield'     => '(0 Clients)',
                'avgOffensive'       => '(0 Clients)',
               
                ]);
        }
        
    }

     public function historyGoalsTeamById(Request $request)
    {
        
        $goalsTotal = Goal::where('team', '=', $request->id)->count();

        /*$goals      = Goal::groupBy('team')->where('player', '=', $user->id)
        ->select('team', DB::raw('count(*) as total'))
        ->get();

        $result=array();

        foreach ($goals as $g) 
        {
            $team = Team::find($g->team);
            $result[] =  array(
                         'goals'      => $g,
                         'team'       => $team,
                         );
        }*/

        return response()->json($goalsTotal);
    }
}
