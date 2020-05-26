<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| MovieController Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all routes for MovieController. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'movies'], function () {
    Route::get('{movie}/favorited-users', 'MovieController@searchFavoritedUsers')
        ->name('movies.searchFavoritedUsers');

    Route::get('{movie}/wishlisted-users', 'MovieController@searchWishlistedUsers')
        ->name('movies.searchWishlistedUsers');

    Route::get('{movie}/actors', 'MovieController@searchActors')
        ->name('movies.searchActors');

    Route::post('{movie}/actors/{actor}', 'MovieController@attachActor')
        ->name('movies.attachActor');

    Route::delete('{movie}/actors/{actor}', 'MovieController@detachActor')
        ->name('movies.detachActor');

    Route::get('{movie}/directors', 'MovieController@searchDirectors')
        ->name('movies.searchDirectors');

    Route::post('{movie}/directors/{director}', 'MovieController@attachDirector')
        ->name('movies.attachDirector');

    Route::delete('{movie}/directors/{director}', 'MovieController@detachDirector')
        ->name('movies.detachDirector');
});

Route::apiResource('/movies', 'MovieController', [
    'parameters' => [
        'movies' => 'movie',
    ],
    'only' => [
        'index','show','store','update','destroy'
    ]
]);
