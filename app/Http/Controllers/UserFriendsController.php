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
use DB;
use Edujugon\PushNotification\PushNotification;

class UserFriendsController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 
         	'signUpClient']]);
    }

    public function addFriend(Request $request)
    {

    	if ($request->id)
    	{
    		$userAuth = JWTAuth::parseToken()->authenticate();
	        $user1 = User::find($userAuth->id);


    		$test = new User();
    		$test->addfriend($user1->id,$request->id);

            // send notification :
            $client = User::find($request->id);

            if ($client->tokenDevice)
            {
                $push = new PushNotification;
                $n = $client->name;
                $push->setMessage([
                  
                    'data' => [
                        'title' => 'Friendship Invitation',
                        'message' => 'You received a request for friendship',
                        ]
                ])
                    ->setApiKey('AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c')
                    ->setDevicesToken([$client->tokenDevice]);

                $push = $push->send();
            }
            

    		return response()->json(" successfully");
    	}else
    	{
    		return response()->json("error");
    	}
    	
    }

    public function getAllMyFriends()
    {
    	$userAuth = JWTAuth::parseToken()->authenticate();
	    $user = User::find($userAuth->id);

	    //$result = $client->friends()->with('user')->where('status', '!=', 0)->get();
		$result = $user->friends()->where('status', '!=', 0)->get();
		return response()->json($result);
    }

    public function searchFriend(Request $request)
    {
    	$name = $request->name;
    	//User::where('column', 'LIKE', '%value%')->get();

    	$customer = DB::table('users')
                  ->where('users.name', 'LIKE', "%$name%")
                  ->where('users.role', '=', "CLIENT")
                  ->orWhere('users.email', 'LIKE', "%$name%")
                  ->get();

		return response()->json($customer);

    /*	$userAuth = JWTAuth::parseToken()->authenticate();
	    $user = User::find($userAuth->id);
	    $client = Client::where('user_id', '=', $user->id)->first();

		$result = $client->friends()->with('user')->where('status', '!=', 0)->get();
		return response()->json($result);*/
    }

    public function getAllFriendsInvitations()
    {
        $userAuth = JWTAuth::parseToken()->authenticate();
        $user = User::find($userAuth->id);
      

        $clients  = $user->friends2()->where('status', '=', 0)->get();

        $numberOFclients = DB::table('user_friend_user')
                          ->where('user_friend_user.user_id_two', '=', $user->id)
                          ->where('user_friend_user.status', '=', 0)
                          ->count();
                  

        return response()->json(['clients'=>$clients,'numberOFclients'=>$numberOFclients]);
    }

    public function getSearchFriendStatus(Request $request)
    {
        if ($request->id)
        {
            $userAuth = JWTAuth::parseToken()->authenticate();
            $user = User::find($userAuth->id);

            $clients  = $user->friends()->where('user_id_two', '=', $request->id)->get();

            return response()->json($clients);
        }else
        {
            return response()->json("error");
        }
    }
   


}
