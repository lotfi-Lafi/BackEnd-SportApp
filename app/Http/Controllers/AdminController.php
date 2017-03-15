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
}
