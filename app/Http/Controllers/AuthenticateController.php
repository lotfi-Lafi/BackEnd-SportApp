<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use AppHttpRequests;
use AppHttpControllersController;
use JWTAuth;
use App\User;
use Tymon\JWTAuthExceptions\JWTException;


class AuthenticateController extends Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'login']]);
    }

    public function index()
    {
        // Retrieve all the users in the database and return them
        $users = User::all();
        return response()->json($users);
    }

    public function getClient()
    {
        // Retrieve all the users in the database and return them
        $clients = User::all();
        return response()->json($clients);
    }

    public function authenticate(Request $request)
    {

        $credentials = $request->only('email', 'password');



        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // search user with email 
        $user = User::where('email','=',$request->get('email'))->first();
        $role = $user->role;
        // if no errors are encountered we can return a JWT
        return response()->json
        ([
            'token' => $token,
            'role'  => $role
         ]);
    }


     public function forgetPassword(Request $request)
    {
        $mdp="test";
        Mail::to($request->get('email'))
            ->send();
        return response()->json(['message' => 'mail sent']);
    }

}
