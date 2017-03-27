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



// authenticate 0
Route::post('authenticate', 'AuthenticateController@authenticate');

/**************************************** Admin ****************************************/
// Accept Organizer : 
Route::post('acceptOrganizer', 'AdminController@acceptOrganizer');
// Refuse Organizer : 
Route::post('refuseOrganizer', 'AdminController@refuseOrganizer');

/**************************************** Organizer ****************************************/
// Register Organizer : 
Route::post('registerOrganizer', 'OrganizerController@signUpOrganizer');

// get all account Organizer Not Accept : 
Route::get('accountNotAccept', 'OrganizerController@accountNotAccept');

// get all account Organizer  Accept : 
Route::get('accountAccept', 'OrganizerController@accountAccept');

/**************************************** Client ****************************************/
// Register Client : 
Route::post('registerClient', 'ClientController@signUpClient');
// get all clients : 
Route::get('allClients', 'ClientController@getAllClients');
