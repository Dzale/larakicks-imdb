<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ActorController Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all routes for ActorController. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'actors'], function () {
    Route::get('{actor}/movies', 'ActorController@searchMovies')
        ->name('actors.searchMovies');

    Route::post('{actor}/movies/{movie}', 'ActorController@attachMovie')
        ->name('actors.attachMovie');

    Route::delete('{actor}/movies/{movie}', 'ActorController@detachMovie')
        ->name('actors.detachMovie');
});

Route::apiResource('/actors', 'ActorController', [
    'parameters' => [
        'actors' => 'actor',
    ],
    'only' => [
        'index','show','store','update','destroy'
    ]
]);
