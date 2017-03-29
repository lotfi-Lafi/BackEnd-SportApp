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
		            $photo  = $request->file('photo');
		            //$user   =  User::find($client->user_id);

		            $user->name         = $request->get('name');
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
			        //else
			            //return response()->json(['Error','no way']);


		            $user->role = "CLIENT";
		            
		            $user->save();

		            return response()->json("Successfully updated!");
		        }
    	}else
    		return response()->json("updated error !");
    	
    }
}
