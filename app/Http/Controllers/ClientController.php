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

        foreach ($user->client->skill as $skil) {

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
            'avgSpeed'          => $avgSpeed,
            'avgEndurance'      => $avgEndurance,
            'avgShoot'          => $avgShoot,
            'avgDribble'        => $avgDribble,
            'avgGoalKeeper'     => $avgGoalKeeper,
            'avgDefender'       => $avgDefender,
            'avgMiddlefield'    => $avgMiddlefield,
            'avgStriker'        => $avgStriker,
            ]);
    }

	 /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUpClient(Request $request)
    {
   	
        /*$rules = array(
            'name'      => 'required',                        
            'email' 	=> 'required|email|unique:users',      
            'password'  => 'required',
            'phone' 	=> 'required',
            'adresse'   => 'required',
            'country' 	=> 'required',
            'city' 		=> 'required',
            'birthday'  => 'required',
            'photo' 	=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );*/

      /*  $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();
            return response()->json($messages);
        }else 
        {*/
            $photo      = $request->file('photo');
            $user = new User;

            $user->name         = $request->get('name');
            $user->email        = $request->get('email');
            $user->password     = Hash::make($request->get('password'));
            $user->phone        = $request->get('phone');
            $user->adresse      = $request->get('adresse');
            $user->country      = $request->get('country');
            $user->city         = $request->get('city');
            $user->birthday     = $request->get('birthday');

        	if($photo)
	        {
	            $input['photoname'] = str_random(50).'.'.$photo->getClientOriginalExtension();

	            $destinationPath = public_path('images');
	            $photo->move($destinationPath, $input['photoname']);

                $user->photo        = 'images/'.$input['photoname'];
	        }
	       /* else
	            return response()->json(['Error','no way']);*/


            $user->role 		= "CLIENT";
            $user->tokenDevice         = $request->tokenDevice;
            
            $user->save();
           
            $client = new Client;

            $client->user_id   = $user->id; 
            

            $client->save();
            return response()->json("Thanks for signing up!");
        }

    
}
