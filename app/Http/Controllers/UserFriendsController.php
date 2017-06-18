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
                $n = $client->name;
                
                $push = new PushNotification;
                

                  $push->setMessage([
                            'notification' => [
                                'title'=>'This is the title',
                                'body'=>'This is the message',
                                'sound' => 'default'
                                ],
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
        $total= array();
    	$userAuth = JWTAuth::parseToken()->authenticate();
	    $user = User::find($userAuth->id);

        $result1 = $user->friends()->where('status', '=', 1)->get();
        $result2 = $user->friends2()->where('status', '=', 1)->get();

        foreach ($result1 as  $value) {
            $total[]=$value;
            
        }

        foreach ($result2 as  $value) {
            $total[]=$value;
            
        }
        return response()->json($total);

    }

    public function searchFriend(Request $request)
    {
        $userAuth = JWTAuth::parseToken()->authenticate();
    	$name = $request->name;
    	$customer = DB::table('users')
                ->where('users.id', '<>', $userAuth->id)
                ->where('users.role', '=', 'CLIENT')
                ->where('users.name', 'LIKE', "%$name%")
                //->orWhere('users.email', 'LIKE', "%$name%")
                ->where('users.role', '=', "CLIENT")
                ->get();
		return response()->json($customer);

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

            $result1  = $user->friends()->where('user_id_two', '=', $request->id)
            ->get();
            $result2  = $user->friends2()->where('user_id_one', '=', $request->id)
            ->get();

            if(!$result1->isEmpty())
            {
                return response()->json($result1[0]->pivot->status);

            }else if(!$result2->isEmpty())
            {
                return response()->json($result2[0]->pivot->status);
            }else
            {   
                $result3= 2;
                return response()->json($result3);
            }
            
        }else
        {
            return response()->json("error");
        }
    }


    public function cancelInvitation(Request $request)
    {

        if ($request->id)
        {
            $userAuth = JWTAuth::parseToken()->authenticate();
            $user1 = User::find($userAuth->id);
            
            DB::table('user_friend_user')
               ->where('user_friend_user.user_id_one', '=',$user1->id)
               ->where('user_friend_user.user_id_two', '=',$request->id)
               ->delete();


            return response()->json(" deleted with successfully");
        }else
        {
            return response()->json("error");
        }
        
    }

    public function refusesInvitation(Request $request)
    {

        if ($request->id)
        {
            $userAuth = JWTAuth::parseToken()->authenticate();
            $user1 = User::find($userAuth->id);
            
            DB::table('user_friend_user')
               ->where('user_friend_user.user_id_one', '=',$request->id)
               ->where('user_friend_user.user_id_two', '=',$user1->id)
               ->delete();


            return response()->json(" deleted with successfully");
        }else
        {
            return response()->json("error");
        }
        
    }

    public function acceptsInvitation(Request $request)
    {

        if ($request->id)
        {
            $userAuth = JWTAuth::parseToken()->authenticate();
            $user1 = User::find($userAuth->id);
            $now = Carbon::now();
            DB::table('user_friend_user')
               ->where('user_friend_user.user_id_one', '=',$request->id)
               ->where('user_friend_user.user_id_two', '=',$user1->id)
               ->update(['status' => 1,'updated_at' => $now->toDateTimeString()]);


            return response()->json(" The invitation to be accepted successfully ");
        }else
        {
            return response()->json("error");
        }
        
    }
   


}
