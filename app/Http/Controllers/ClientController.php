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

class ClientController extends Controller
{
    public function __construct()
    {
         $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 
         	'signUpClient']]);
    }

    public function getAllClients()
    {
    	$clients = Client::with('user')->get();
    	//$clients = Client::all()->load('user');

    	return response()->json($clients);
    	
    }

    public function getClient(Request $request)
    {
        $user = User::where('id', '=', $request->id)
        ->with('client')
        ->get()
        ->first();

        return response()->json($user);
        
    }

    public function getSingleEvaluationFriend(Request $request)
    {
        $user = User::where('id', '=', $request->id)
        ->with('client.skill','client.position')
        ->get()
        ->first();

        $avgSpeed=0;
        $avgEndurance =0;
        $avgShoot =0;
        $avgDribble=0;
        $avgGoalKeeper=0;
        $avgDefender=0;
        $avgMiddlefield=0;
        $avgStriker=0;

        $countTotal =0;


        foreach ($user->client->skill as $skil) {

            $countTotal      = $skil->count();


            $avgSpeed       = $skil->sum('speed') / $skil->count();
            $avgEndurance   = $skil->sum('endurance') / $skil->count();
            $avgShoot       = $skil->sum('shoot') / $skil->count();
            $avgDribble     = $skil->sum('dribble') / $skil->count();
        }

        foreach ($user->client->position as $posit) {

            $avgGoalKeeper        = $posit->sum('goalKeeper') / $posit->count();
            $avgDefender          = $posit->sum('defender') / $posit->count();
            $avgMiddlefield       = $posit->sum('middlefield') / $posit->count();
            $avgStriker           = $posit->sum('striker') / $posit->count();
        }


        return response()->json([
            'user'              => $user, 
            'avgSpeed'          => number_format($avgSpeed,2).'/10.  ('.$countTotal.' friends)',
            'avgEndurance'      => number_format($avgEndurance,2).'/10.  ('.$countTotal.' friends)',
            'avgShoot'          => number_format($avgShoot,2).'/10.  ('.$countTotal.' friends)',
            'avgDribble'        => number_format($avgDribble,2).'/10.  ('.$countTotal.' friends)',
            'avgGoalKeeper'     => number_format($avgGoalKeeper,2).'/10.  ('.$countTotal.' friends)',
            'avgDefender'       => number_format($avgDefender,2).'/10.  ('.$countTotal.' friends)',
            'avgMiddlefield'    => number_format($avgMiddlefield,2).'/10.  ('.$countTotal.' friends)',
            'avgStriker'        => number_format($avgStriker,2).'/10.  ('.$countTotal.' friends)',
            ]);
    }

	 /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUpClient(Request $request)
    {
   	
       
            $user = new User;

            $user->name         = $request->get('name');
            $user->email        = $request->get('email');
            $user->password     = Hash::make($request->get('password'));
            $user->role 		= "CLIENT";
            $user->tokenDevice  = $request->tokenDevice;
            
            $user->save();
           
            $client = new Client;

            $client->user_id   = $user->id; 
            

            $client->save();
            return response()->json("Thanks for signing up!");
        }

    
}
