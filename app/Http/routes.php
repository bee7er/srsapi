<?php

use App\Http\Controllers\Api\RendersController;
use App\Http\Controllers\Api\RegistrationsController;
use App\Http\Controllers\Api\UploadsController;
use App\Http\Controllers\Api\BlockedUsersController;
use App\Http\Controllers\Api\TeamsController;
use Illuminate\Http\Request;

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

// Make the following classes available in the views
Route::resource('renders', 'RendersController');
Route::resource('users', 'UsersController');
Route::resource('teams', 'TeamsController');
Route::resource('team_members', 'TeamMembersController');

Route::get('cookies', 'PagesController@cookies');

Route::get('reassign', 'RendersController@reassign');
Route::get('cancel', 'RendersController@cancel');
Route::get('remove', 'TeamMembersController@remove');
Route::get('select', 'TeamMembersController@select');
Route::get('add', 'TeamMembersController@add');
Route::get('membership', 'TeamMembersController@membership');
Route::get('toggleTeamStatus', 'TeamsController@toggleTeamStatus');
Route::get('toggleMembershipStatus', 'TeamMembersController@toggleMembershipStatus');
Route::get('toggleTeamMemberStatus', 'TeamMembersController@toggleTeamMemberStatus');
Route::get('toggleBlockedUserStatus', 'TeamMembersController@toggleBlockedUserStatus');

// Authentication, registering 2 controllers
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Register a slave user node
Route::post('/api1/register', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->register($request)
    );
});

// Slave user notifying master that they are awake but not available for rendering
Route::post('/api1/awake', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->awake($request)
    );
});

// Slave user notifying master that they are not currently rendering
Route::get('/api1/available', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->available($request)
    );
});

// Slave user notifying master that they are not currently rendering
Route::post('/api1/available', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->available($request)
    );
});

// Slave user requesting status of remote and local renders
Route::post('/api1/status', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->status($request)
    );
});

// Slave user notifying master that they are rendering
Route::post('/api1/rendering', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->rendering($request)
    );
});

// Slave user notifying master that the render they were working on is now complete
Route::post('/api1/complete', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->complete($request)
    );
});

// Slave user notifying master that the downloading of an image is now complete
Route::post('/api1/downloaded', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->downloaded($request)
    );
});

// Slave user requesting a render to be processed
Route::post('/api1/render', function (Request $request) {
    return response()->json(
        (new RendersController)->render($request)
    );
});

// Slave user requesting a new team to be created
Route::post('/api1/new_team', function (Request $request) {
    return response()->json(
        (new TeamsController)->newTeam($request)
    );
});

// Slave user requesting a new user to be created
Route::post('/api1/new_user', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->newUser($request)
    );
});

// Uploading project with assets files
Route::post('/projects', function (Request $request) {
    return response()->json(
        (new UploadsController)->handleUploadProjects($request)
    );
});

// Uploading rendered image files
Route::post('/results', function (Request $request) {
    return response()->json(
        (new UploadsController)->handleUploadResults($request)
    );
});

// Block a user from doing renders for the requesting user
Route::post('/block', function (Request $request) {
    return response()->json(
        (new BlockedUsersController)->block($request)
    );
});

// Unblock a user to do renders for the requesting user
Route::post('/unblock', function (Request $request) {
    return response()->json(
        (new BlockedUsersController)->unblock($request)
    );
});

// Returns a list of renders currently in the queue
Route::get('/api1/renders', function () {
    return response()->json(
        (new RendersController)->index()
    );
});

// Returns a list of renders currently in the queue
Route::get('/api1/renders/request', function (Request $request) {
    return response()->json(
        (new RendersController)->renderRequest($request)
    );
});

// Returns a list of all users registered in the system
Route::get('/api1/users', function () {
    return response()->json(
        (new RendersController)->index()
    );
});

Route::get('/api1/test', function () {
    return response()->json(
        (new RendersController)->index()
    );
});
