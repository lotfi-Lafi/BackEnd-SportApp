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
use App\Position;
use App\Skill;
use App\Goal;
use App\Card;
use App\Team;
use DB;
class ClientProfilController extends Controller
{
    public function __construct()
    {
         $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 
         	'signUpClient']]);
    }

    public function getDataProfil(Request $request)
    {

    	//$client = Client::where('id', '=', $request->id)->with('user')->get();
    	$userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);

    	if ($user)
    	{
    		return response()->json($user);
    	}else
    		return response()->json("data error !");
    	
    }

    public function editProfil(Request $request)
    {   
    	$rules = array(
            'name'      => 'required',                        
        );

    	$userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);

        $client = Client::where('user_id', '=', $user->id)->first();
    	
    	if ($client)
    	{
    		//dd($client->user_id);
    		$validator = Validator::make($request->all(), $rules);
		        if ($validator->fails()) {
		            // get the error messages from the validator
		            $messages = $validator->messages();
		            return response()->json($messages);
		        }else 
		        {
		            //$photo  = $request->file('photo');
		            //$user   =  User::find($client->user_id);

		            $user->name         = $request->get('name');
		            $user->phone        = $request->get('phone');
		            $user->adresse      = $request->get('adresse');
		            $user->country      = $request->get('country');
		            $user->city         = $request->get('city');
		            $user->birthday     = $request->get('birthday');
                    $user->photo        = $request->get('photo');

		        	/*if($photo)
			        {
			            $input['photoname'] = str_random(50).'.'.$photo->getClientOriginalExtension();

			            $destinationPath = public_path('images');
			            $photo->move($destinationPath, $input['photoname']);

		                $user->photo        = 'images/'.$input['photoname'];
			        }*/
			        //else
			            //return response()->json(['Error','no way']);


		            $user->role = "CLIENT";
		            
		            $user->save();

		            return response()->json("Successfully updated!");
		        }
    	}else
    		return response()->json("updated error !");
    	
    }

    public function addEvaluation (Request $request)
    {

        $client = Client::where('user_id', '=', $request->id)->get()->first();
     
        if ($client)
        {
            $position = new Position;

            $position->client_id    = $client->id;
            $position->goalKeeper   = $request->goalKeeper;
            $position->defender     = $request->defender;
            $position->middlefield  = $request->middlefield;
            $position->striker      = $request->striker;

            $position->save();

            $skill = new Skill;

            $skill->client_id     = $client->id;
            $skill->speed         = $request->speed;
            $skill->endurance     = $request->endurance;
            $skill->shoot         = $request->shoot;
            $skill->dribble       = $request->dribble;

            $skill->save();

            return response()->json('Successfully add evaluation !');
        }
        else
        return response()->json("error !!");
    }

    public function historyGoals()
    {
        $userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);
        
        $goalsTotal = Goal::where('player', '=', $user->id)->count();

        $goals      = Goal::groupBy('team')->where('player', '=', $user->id)
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
        }

        return response()->json(['result' => $result, 'goalTotal' => $goalsTotal]);
    }

    public function historyGoalsById(Request $request)
    {
        $user = User::find($request->id);
        
        $goalsTotal = Goal::where('player', '=', $user->id)->count();

        $goals      = Goal::groupBy('team')->where('player', '=', $user->id)
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
        }

        return response()->json(['result' => $result, 'goalTotal' => $goalsTotal]);
    }

    public function historyCards()
    {
        $userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);
        
        $cardsTotal = Card::where('player', '=', $user->id)->count();

        $cards      = Card::groupBy('color')->where('player', '=', $user->id)
        ->select('color', DB::raw('count(*) as total'))
        ->get();

        return response()->json(['cards' => $cards, 'cardsTotal' => $cardsTotal]);
    }

    public function historyCardsById(Request $request)
    {
        $user = User::find($request->id);
        
        $cardsTotal = Card::where('player', '=', $user->id)->count();

        $cards      = Card::groupBy('color')->where('player', '=', $user->id)
        ->select('color', DB::raw('count(*) as total'))
        ->get();

        return response()->json(['cards' => $cards, 'cardsTotal' => $cardsTotal]);
    }

    
    
}
