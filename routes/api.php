<?php

use App\Http\Middleware\BearerRequire;
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

Route::get('status', 'StatusController')
    ->name('status');

Route::post('users', 'UserController@update')
    ->name('user.update');

Route::get('places', 'PlaceSearchController')
    ->name('place.search');

Route::prefix('rooms')
    ->middleware(BearerRequire::class)
    ->group(function () {
        Route::post('', 'RoomController@create')
            ->name('room.create');
        Route::get('{id}', 'RoomController@view')
            ->name('room.view');
        Route::post('{id}/activities', 'ActivityController@create')
            ->name('activity.create');
    });

Route::prefix('activities')
    ->middleware(BearerRequire::class)
    ->group(function () {
        Route::post('{id}/users', 'ActivityUserController')
            ->name('activity.user');
    });
