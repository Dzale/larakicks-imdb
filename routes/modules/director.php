<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| DirectorController Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all routes for DirectorController. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'directors'], function () {
    Route::get('{director}/movies', 'DirectorController@searchMovies')
        ->name('directors.searchMovies');

    Route::post('{director}/movies/{movie}', 'DirectorController@attachMovie')
        ->name('directors.attachMovie');

    Route::delete('{director}/movies/{movie}', 'DirectorController@detachMovie')
        ->name('directors.detachMovie');
});

Route::apiResource('/directors', 'DirectorController', [
    'parameters' => [
        'directors' => 'director',
    ],
    'only' => [
        'index','show','store','update','destroy'
    ]
]);
