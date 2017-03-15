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

class OrganizerController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 'signUpClient']]);
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
            'adresse'   => 'required',
            'country' 	=> 'required',
            'city' 		=> 'required',
            'birthday'  => 'required',
            'photo' 	=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // get the error messages from the validator
            $messages = $validator->messages();
            return response()->json($messages);
        }else 
        {
            $photo      = $request->file('photo');
            $etat       = 0;

        	if($photo)
	        {
	            $input['photoname'] = str_random(50).'.'.$photo->getClientOriginalExtension();

	            $destinationPath = public_path('images');
	            $photo->move($destinationPath, $input['photoname']);

	        }
	        else
	            return response()->json(['Error','no way']);

            $user = new User;
       
            
            $user->name         = $request->get('name');
            $user->email 	    = $request->get('email');
            $user->password     = Hash::make($request->get('password'));
            $user->phone 	    = $request->get('phone');
            $user->adresse      = $request->get('adresse');
            $user->country 	    = $request->get('country');
            $user->city 		= $request->get('city');
            $user->birthday 	= $request->get('birthday');
            $user->photo 	    = 'images/'.$input['photoname'];
            $user->role 		= "ORGANIZER";
            
            $user->save();
           
            $organizer = new Organizer;

            $organizer->user_id   = $user->id; 
            $organizer->etat      = $etat;
            

            $organizer->save();
            return response()->json("Thanks for signing up! thanks for waiting to admin accept your account");
        }
 
    }

    public function accountNotAccept()
    {
    	$organizer = Organizer::where('etat','=',0)->with('user')->get();
    	return response()->json($organizer);
    }

    public function accountAccept()
    {
    	$organizer = Organizer::where('etat','=',1)->with('user')->get();
    	return response()->json($organizer);
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
