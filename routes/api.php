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

// authenticate 0
Route::post('authenticate', 'AuthenticateController@authenticate');

/**************************************** Admin ****************************************/

// Uploadd image   : 
Route::post('uploadImage', 'AdminController@uploadImage');
// Accept Organizer : 
Route::post('acceptOrganizer', 'AdminController@acceptOrganizer');
// Refuse Organizer : 
Route::post('refuseOrganizer', 'AdminController@refuseOrganizer');
// delete Organizer : 
Route::post('deleteOrganizer', 'AdminController@deleteOrganizer');

// send notification   : 
Route::post('sendNotification', 'AdminController@sendNotification');
/**************************************** Organizer ****************************************/

// get Organizer : 
Route::get('getOrganizer', 'OrganizerController@getOrganizer');

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

// get  client : 
Route::get('client', 'ClientController@getClient');

// get Single Evaluation Friend : 
Route::get('evaluationFriend', 'ClientController@getSingleEvaluationFriend');

// get profil Data  : 
Route::get('profil', 'ClientProfilController@getDataProfil');

// edit profil   : 
Route::post('editProfil', 'ClientProfilController@editProfil');

// add Friend   : 
Route::post('addFriend', 'UserFriendsController@addFriend');

// get All My Friend   : 
Route::get('allFriends', 'UserFriendsController@getAllMyFriends');

// search Friend   : 
Route::post('searchFriend', 'UserFriendsController@searchFriend');

// get All Friends Invitations   : 
Route::get('allFriendsInvitations', 'UserFriendsController@getAllFriendsInvitations');

// add Evaluation   : 
Route::post('addEvaluation', 'ClientProfilController@addEvaluation');

/**************************************** Team ****************************************/
// Create Team : 
Route::post('createTeam', 'TeamController@createTeam');
// get Team : 
Route::get('team', 'TeamController@getTeam');
// get all Teams : 
Route::get('allTeams', 'TeamController@getAllTeams');
// Delete Team : 
Route::post('deleteTeam', 'TeamController@deleteTeam');