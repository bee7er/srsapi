<?php

use App\Http\Controllers\Api\Renders\RendersController;
use App\Http\Controllers\Api\Renders\RegistrationsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;

Route::get('/', 'HomeController@index');

Route::get('home', 'HomeController@index');

Route::get('contact', 'ContactController@index');

Route::get('cookies', 'PagesController@cookies');

Route::resource('users', 'UsersController');

// Authentication, registering 2 controllers
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
|
*/

//Route::middleware('auth.basic')->group(function () {
//    Route::get('/', (new UsersController)->index());
//});

// No acrtual action associated with this invocation
//Route::get('/', function () {
//    return response()->json([
//        'message' => 'Hello. The website is working.'
//    ]);
//});

// Register a slave user node
Route::post('/api1/register', function (Request $request) {
    return response()->json(
        (new RegistrationsController)->register($request)
    );
});

// Slave user notifying master that they are not currently rendering
Route::post('/api1/available', function (Request $request) {
    return response()->json(
        (new RendersController)->available($request)
    );
});

// Slave user notifying master that they are rendering
Route::post('/api1/rendering', function (Request $request) {
    return response()->json(
        (new RendersController)->rendering($request)
    );
});

// Slave user notifying master that the render they were working on is now complete
Route::post('/api1/complete', function (Request $request) {
    return response()->json(
        (new RendersController)->complete($request)
    );
});

// Slave user requesting a render to be processed
Route::post('/api1/render', function (Request $request) {
    return response()->json(
        (new RendersController)->render($request)
    );
});




//*******************
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

//Route::group(['middleware' => 'auth:basic'],function(){
//    return response()->json(['data' => 'dddjjj']);
//});

//Route::middleware('auth.basic')->group(function () {
//    Route::apiResource('books', BooksController::class);
//});
//
//Route::get('/', function () {
//    return response()->json([
//        'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
//    ]);
//});