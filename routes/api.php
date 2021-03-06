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

// authenticate 
Route::post('authenticate', 'AuthenticateController@authenticate');

// logout
Route::get('logout', 'AuthenticateController@logoutt');

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
Route::get('myEvaluation', 'ClientController@myEvaluation');

// get Single Evaluation Friend : 
Route::get('evaluationFriend', 'ClientController@getSingleEvaluationFriend');

// get profil Data  : 
Route::get('profil', 'ClientProfilController@getDataProfil');

// edit profil   : 
Route::post('editProfil', 'ClientProfilController@editProfil');

// add Friend   : 
Route::post('addFriend', 'UserFriendsController@addFriend');

// cancel Invitation   : 
Route::get('cancelInvitation', 'UserFriendsController@cancelInvitation');

// refuses Invitation   : 
Route::get('refusesInvitation', 'UserFriendsController@refusesInvitation');

// Accepts Invitation   : 
Route::get('acceptsInvitation', 'UserFriendsController@acceptsInvitation');

// get All My Friend   : 
Route::get('allFriends', 'UserFriendsController@getAllMyFriends');

// search Friend   : 
Route::post('searchFriend', 'UserFriendsController@searchFriend');

// get All Friends Invitations   : 
Route::get('allFriendsInvitations', 'UserFriendsController@getAllFriendsInvitations');

// get status  searchFriend  : 
Route::get('searchFriendStatus', 'UserFriendsController@getSearchFriendStatus');

// add Evaluation   : 
Route::post('addEvaluation', 'ClientProfilController@addEvaluation');

// get history Goals   : 
Route::get('historyGoals', 'ClientProfilController@historyGoals');

// get history Goals By Id: 
Route::get('historyGoalsById', 'ClientProfilController@historyGoalsById');

// get history cards : 
Route::get('historyCards', 'ClientProfilController@historyCards');

// get history Goals By Id : 
Route::get('historyCardsById', 'ClientProfilController@historyCardsById');

/**************************************** Team ****************************************/

// Create Team : 
Route::post('createTeam', 'TeamController@createTeam');

// get Team : 
Route::get('team', 'TeamController@getTeam');

// get all Teams : 
Route::get('allTeams', 'TeamController@getAllTeams');

// get all My Teams : 
Route::get('allMyTeams', 'TeamController@getAllMyTeams');

// permission Create Team : 
Route::get('permissionCreateTeam', 'TeamController@permissionCreateTeam');

// Delete Team : 
Route::post('deleteTeam', 'TeamController@deleteTeam');

// search Teams   : 
Route::post('searchTeams', 'TeamController@searchTeams');

// search Teams  all : 
Route::get('searchTeamsAll', 'TeamController@searchTeamsAll');

// add Evaluation To Team : 
Route::post('addEvaluationToTeam', 'TeamController@addEvaluationToTeam');

// get Single Evaluation Team : 
Route::get('evaluationTeam', 'TeamController@getEvaluationTeam');

// get history Goals Team By Id: 
Route::get('historyGoalsTeamById', 'TeamController@historyGoalsTeamById');

/**************************************** Competition ****************************************/

// add Competition : 
Route::post('addCompetition', 'CompetitionController@addCompetition');

// add To Competition Existante : 
Route::post('addToCompetitionExistante', 'CompetitionController@addToCompetitionExistante');

// valid Competition  : 
Route::get('validCompetition', 'CompetitionController@validCompetition');

// cancel Competition  : 
Route::get('cancelCompetition', 'CompetitionController@cancelCompetition');

// get Competition Construction : 
Route::get('competitionConstruction', 'CompetitionController@getCompetitionConstruction');

// get Competition Construction Accepted : 
Route::get('competitionConstructionAccepted', 'CompetitionController@getCompetitionConstructionAccepted');

// get Competition Construction Refused : 
Route::get('competitionConstructionRefused', 'CompetitionController@getCompetitionConstructionRefused');

// get Competition Construction Current : 
Route::get('competitionConstructionCurrent', 'CompetitionController@getCompetitionConstructionCurrent');

// get Teams Not Invited To Competition : 
Route::get('teamsNotInvitedToCompetition', 'CompetitionController@getTeamsNotInvitedToCompetition');

// get Teams of Competition valide : 
Route::get('teamsOfCompetitionValide', 'CompetitionController@teamsOfCompetitionValide');

// get my Competition By Team : 
Route::get('myCompetitionByTeam', 'CompetitionController@myCompetitionByTeam');

// get author competition : 
Route::get('allCompetitonValid', 'CompetitionController@allCompetitonValid');

// get matchs By Competition : 
Route::get('matchsByCompetition', 'CompetitionController@matchsByCompetition');

// get goals By Match : 
Route::get('goalsByMatch', 'CompetitionController@goalsByMatch');

/**************************************** Match ****************************************/

// get Match : 
Route::get('getMatch', 'MatchController@getMatch');

// create Match : 
Route::post('createMatch', 'MatchController@addMatch');

// get Live Matchs : 
Route::get('liveMatchs', 'MatchController@getLiveMatchs');

// get Live Match : 
Route::get('liveMatch', 'MatchController@getLiveMatch');

// Edit Resultat Match : 
Route::post('editResultatMatch', 'MatchController@editResultatMatch');

// add Info Reporter : 
Route::post('addInfoReporter', 'MatchController@addInfoReporter');

/**************************************** Accueil Mobile ****************************************/

// resultat Live Matchs : 
Route::get('resultatLiveMatchs', 'AccueilController@getResultatLiveMatchs');