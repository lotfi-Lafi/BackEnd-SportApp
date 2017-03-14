<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    public function authenticate(Request $request)
    {
        $credentials = Request::only['email','password'];
        try{
            if ($token = JWTAuth::attempt($credentials))
            {
                return $this->response->error(['error' => 'User credentials are not correct !'], 401)
            }


        }catch (JWTException $ex)
        {
            return $this->response->error(['error' => 'Something went wrong !'], 500);

        }

        return $this->response->error(compact('token'));
    }
}
