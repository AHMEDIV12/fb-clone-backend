<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::put('updateUser/{id}', 'AuthController@updateUser');
    Route::put('follow/{id}', 'AuthController@follow');
    Route::put('unfollow/{id}', 'AuthController@unfollow');
    Route::post('poststore', 'PostsController@store');
    Route::post('postupdate/{id}', 'PostsController@update');
    Route::post('postdelete/{id}', 'PostsController@destroy');
    Route::get('postshow/{id}', 'PostsController@show');
    Route::post('postlike/{id}', 'PostsController@postlike');
    Route::get('followers/{id}', 'UsersController@userFollowers');
    Route::get('followings/{id}', 'UsersController@userFollowings');

});

Route::get('userposts/{id}', 'PostsController@userposts');
Route::get('user/{id}', 'UsersController@user');
Route::post('chekuserfollower/{id}', 'UsersController@chekuserfollower');