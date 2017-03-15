<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();


});

header('Access-Control-Allow-Origin: http://localhost:8080');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

// authenticate : 
Route::post('authenticate', 'AuthenticateController@authenticate');

// Register Organizer : 
Route::post('registerOrganizer', 'OrganizerController@signUpOrganizer');


// Register Client : 
Route::post('registerClient', 'ClientController@signUpClient');
// get all clients : 
Route::get('AllClients', 'ClientController@getAllClients');
