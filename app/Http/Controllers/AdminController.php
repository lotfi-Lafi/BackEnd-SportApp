<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Client;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuthExceptions\JWTException;
use JWTAuth;
use App\User;
use App\Organizer;
use Edujugon\PushNotification\PushNotification;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'login','signUpOrganizer', 'signUpClient']]);
    }

    public function acceptOrganizer(Request $request)
    {
    	
    	$organizer = Organizer::find($request->id);
    	if ($organizer->etat == 0)
    	{
    		Organizer::find($organizer->id)->update(['etat' => 1]);
    		return response()->json("updating success !");
    	}else
    		return response()->json("updating error !");
    	
    }

    public function refuseOrganizer(Request $request)
    {
    	
    	$organizer = Organizer::find($request->id);
    	if ($organizer->etat == 0)
    	{
    		Organizer::find($organizer->id)->delete();
    		return response()->json("deleted successfully !");
    	}else
    		return response()->json("deleted error !");
    }


    public function deleteOrganizer(Request $request)
    {
        $organizer = Organizer::find($request->id);
        if ($organizer->etat == 1)
        {
            Organizer::find($organizer->id)->delete();
            return response()->json("deleted successfully !");
        }else
            return response()->json("deleted error !");
    }

  
    public function uploadImage(Request $request)
    {
        $this->validate($request, [

            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);

        $logo = $request->file('logo');

        if($logo)
        {
            $input['photoname'] = str_random(50).'.'.$logo->getClientOriginalExtension();

            $destinationPath = public_path('images');
            $logo->move($destinationPath, $input['photoname']);

            $result        = 'images/'.$input['photoname'];
            return response()->json($result);
        }else
        
        return response()->json(['Error','no way']);
    }


    public function sendNotification(Request $request)
    {
        $user = User::where('id', '=', $request->id)->first();

        $push = new PushNotification;

        $push->setMessage([
                'notification' => [
                    'title'=>'This is the title',
                    'body'=>'This is the message',
                    'sound' => 'default'
                    ],
            'data' => [
                    'title' => 'ya saberrrrrr',
                    'message' => 'value2'
                    ]
            ])
                ->setApiKey('AAAAqyAkYnE:APA91bGeKs2GT74IG_jCauw7EevaRZJ77CojxCRd3QpbyZ6smEmfjU451iS0ZuhdBUCKpy21KYAi8EENiCJL_AP-vaXL8jJdoH9uNb3g-jVtYWJO4G1kEyLaae4dRAuY3o7OXERLkL_c')
                ->setDevicesToken([$user->tokenDevice]);


        $push = $push->send();

    return response()->json($push->getFeedback());


      
  
       
    }



}
