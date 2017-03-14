<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Organizer;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuthExceptions\JWTException;
use JWTAuth;
use App\User;
use App\Client;

class RegisterOrganizerController extends Controller
{

    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
       //$this->middleware('jwt.auth', ['except' => ['authenticate']]);
        $this->middleware('auth:api', ['except' => ['authenticate']]);
    }

	 /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUpOrganizer(Request $request)
    {
    	
        /*  get token :     
         dd(getallheaders()["X-Auth-Token"]);*/    	
        $rules = array(
            'name'      => 'required',                        
            'email' 	=> 'required|email|unique:users',      
            'password'  => 'required',
            'phone' 	=> 'required',
            'adresse'     => 'required',
            'country' 	=> 'required',
            'city' 		=> 'required',
            'birthday'  => 'required',
            'photo' 	=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'etat' 		=> 'required|max:1|integer'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();
            return response()->json($messages);
        } else {
            $name 	= $request->get('name');
            $email 		= $request->get('email');
            $password 	= $request->get('password');
            $phone 		= $request->get('phone');
            $country 	= $request->get('country');
            $city 		= $request->get('city');
            $birthday 	= $request->get('birthday');
            $photo 		= $request->file('photo');
            $etat 		= $request->get('etat');

/*$userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);*/
            //$user = JWTAuth::parseToken()->authenticate();
        	$now = Carbon::now();
        	if($photo)
	        {
	            $input['photoname'] = str_random(40).'.'.$photo->getClientOriginalExtension();

	            $destinationPath = public_path('images');
	            $photo->move($destinationPath, $input['photoname']);
	            /*
	            $user->photo ='images/'.$input['photoname'];
	            $user->save();
	             return response()->json(['success','Logo Upload successful']); */
	        }
	        else
	            return response()->json(['Error','no way']);

            $organizer = [
            	'name'      => $name, 
            	'email' 	=> $email, 
            	'password'  => Hash::make($password),
            	'phone' 	=> $phone,
            	'country' 	=> $country,
                'city' 		=> $city, 
                'birthday' 	=> $birthday,
                'photo' 	=> 'images/'.$input['photoname'],
                'etat' 		=> $etat
            ];
            Organizer::create($organizer);
            return response()->json("Thanks for signing up!");
        }

    }


    public function signUpOrganizerWithFacebook(ProviderUser $providerUser)
    {
        //dd('res'.$providerUser);
        $user = DB::table('users')->where('provider', 'facebook')
            ->where('provider_user_id', $providerUser->getId())
            ->first();
        /**
         * if user is registred
         */
        if ($user) {

            $req = new Request((array)$user);
            // dd($user);
            return $this->authenticate_social($req);
        } /**
         * if user is not registred
         */
        else {
            $user1 = DB::table('users')->where('email', $providerUser->getEmail())->first();
            if (!$user1) {
                //dd($providerUser);
                $user1 = ['name' => $providerUser->getName(), 'email' => $providerUser->getEmail(), 'password' => $providerUser->getId(),
                    'provider_user_id' => $providerUser->getId(),
                    'provider' => 'facebook'];
            }
            //dd($user1);
            $req1 = new Request($user1);
            if ($user) {
                return $this->authenticate($req1);
            }
            return $this->signUp($req1);

        }

    }
     
}
