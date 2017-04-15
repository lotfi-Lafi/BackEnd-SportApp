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

	        //$client = Client::where('user_id', '=', $user->id)->first();

    		$test = new User();
    		$test->addfriend($user1->id,$request->id);

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

        $clients = DB::table('user_friend_user')
                  ->where('user_friend_user.user_id_one', '=', $user->id)
                  ->where('user_friend_user.status', '=', 0)
                  ->get();

        $numberOFclients = DB::table('user_friend_user')
                          ->where('user_friend_user.user_id_one', '=', $user->id)
                          ->where('user_friend_user.status', '=', 0)
                          ->count();
                  

        return response()->json(['clients'=>$clients,'numberOFclients'=>$numberOFclients]);
    }
   


}
